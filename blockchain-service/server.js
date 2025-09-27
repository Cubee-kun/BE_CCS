import express from 'express';
import cors from 'cors';
import { ethers } from 'ethers';
import multer from 'multer';
import { create } from 'ipfs-http-client';
import crypto from 'crypto';
import contractABI from './CCSRegistry.abi.json' assert { type: 'json' };

const app = express();
app.use(cors());
app.use(express.json());

// --- ENVIRONMENT CHECK ---
const {
    RPC_URL,
    PRIVATE_KEY,
    CONTRACT_ADDRESS,
    IPFS_URL,
    PORT = 4000
} = process.env;

if (!RPC_URL || !PRIVATE_KEY || !CONTRACT_ADDRESS || !IPFS_URL) {
    console.error('❌ Missing required environment variables. Please set RPC_URL, PRIVATE_KEY, CONTRACT_ADDRESS, IPFS_URL.');
    process.exit(1);
}

// --- BLOCKCHAIN & IPFS SETUP ---
const provider = new ethers.JsonRpcProvider(RPC_URL);
const wallet = new ethers.Wallet(PRIVATE_KEY, provider);
const contract = new ethers.Contract(CONTRACT_ADDRESS, contractABI, wallet);
const ipfs = create({ url: IPFS_URL });
const upload = multer({ storage: multer.memoryStorage() });

// --- HELPERS ---
function generateHash(data) {
    return '0x' + crypto.createHash('sha256').update(JSON.stringify(data)).digest('hex');
}

// --- HEALTH CHECK ---
app.get('/health', (req, res) => {
    res.json({ status: 'ok', network: RPC_URL, contract: CONTRACT_ADDRESS });
});

// --- STORE DOCUMENT (IPFS + Blockchain) ---
app.post('/store-document', upload.single('file'), async (req, res) => {
    try {
        const { documentType, metadata } = req.body;
        if (!documentType) return res.status(400).json({ success: false, error: 'documentType is required' });

        let cid = '';
        let dataToHash = {};

        if (req.file) {
            const result = await ipfs.add(req.file.buffer);
            cid = result.cid.toString();
            dataToHash = {
                cid,
                filename: req.file.originalname,
                documentType,
                metadata: metadata ? JSON.parse(metadata) : {}
            };
        } else {
            dataToHash = {
                documentType,
                metadata: metadata ? JSON.parse(metadata) : {}
            };
        }

        const dataHash = generateHash(dataToHash);

        const tx = await contract.storeDocument(
            cid,
            dataHash,
            documentType,
            metadata || '{}'
        );
        const receipt = await tx.wait();

        // Ambil event DocumentStored
        let documentId = null;
        for (const log of receipt.logs) {
            try {
                const parsed = contract.interface.parseLog(log);
                if (parsed.name === 'DocumentStored') {
                    documentId = parsed.args.id;
                    break;
                }
            } catch {}
        }

        res.json({
            success: true,
            documentId: documentId ? documentId.toString() : null,
            cid,
            dataHash,
            txHash: receipt.transactionHash,
            blockNumber: receipt.blockNumber
        });

    } catch (error) {
        console.error('Error storing document:', error);
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// --- GET DOCUMENT BY ID ---
app.get('/document/:id', async (req, res) => {
    try {
        const { id } = req.params;
        const document = await contract.getDocument(id);

        let ipfsData = null;
        if (document.cid) {
            try {
                const chunks = [];
                for await (const chunk of ipfs.cat(document.cid)) {
                    chunks.push(chunk);
                }
                ipfsData = Buffer.concat(chunks);
            } catch (ipfsError) {
                console.warn('IPFS fetch failed:', ipfsError.message);
            }
        }

        res.json({
            success: true,
            document: {
                id: document.id.toString(),
                cid: document.cid,
                dataHash: document.dataHash,
                owner: document.owner,
                timestamp: document.timestamp.toString(),
                documentType: document.documentType,
                metadata: document.metadata,
                verified: document.verified
            },
            ipfsData: ipfsData ? ipfsData.toString('base64') : null
        });

    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// --- REGISTER IDENTITY ---
app.post('/register-identity', async (req, res) => {
    try {
        const { name, role } = req.body;
        if (!name || !role) return res.status(400).json({ success: false, error: 'name and role are required' });

        const tx = await contract.registerIdentity(name, role);
        const receipt = await tx.wait();

        res.json({
            success: true,
            txHash: receipt.transactionHash,
            wallet: wallet.address
        });

    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// --- VERIFY DOCUMENT (ADMIN ONLY) ---
app.post('/verify-document/:id', async (req, res) => {
    try {
        const { id } = req.params;
        const tx = await contract.verifyDocument(id);
        const receipt = await tx.wait();

        res.json({
            success: true,
            txHash: receipt.transactionHash
        });

    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// --- GET DOCUMENTS BY TYPE ---
app.get('/documents/type/:type', async (req, res) => {
    try {
        const { type } = req.params;
        const documentIds = await contract.getDocumentsByType(type);

        const documents = await Promise.all(
            documentIds.map(async (id) => {
                const doc = await contract.getDocument(id);
                return {
                    id: doc.id.toString(),
                    cid: doc.cid,
                    owner: doc.owner,
                    timestamp: doc.timestamp.toString(),
                    documentType: doc.documentType,
                    verified: doc.verified,
                    metadata: doc.metadata
                };
            })
        );

        res.json({
            success: true,
            documents
        });

    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// --- GET TRANSACTION STATUS ---
app.get('/transaction/:hash', async (req, res) => {
    try {
        const { hash } = req.params;
        const receipt = await provider.getTransactionReceipt(hash);

        res.json({
            success: true,
            receipt: receipt ? {
                transactionHash: receipt.transactionHash,
                blockNumber: receipt.blockNumber,
                status: receipt.status,
                gasUsed: receipt.gasUsed.toString()
            } : null
        });

    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// --- START SERVER ---
app.listen(PORT, () => {
    console.log(`🚀 Blockchain service running on port ${PORT}`);
    console.log(`📄 Contract: ${CONTRACT_ADDRESS}`);
    console.log(`🔗 Network: ${RPC_URL}`);
});
