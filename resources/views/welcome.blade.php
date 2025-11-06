<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CCS API</title>
    <style>
      body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif; margin: 0; padding: 2rem; background: #0b1220; color: #e6edf3; }
      .card { max-width: 720px; margin: 10vh auto; padding: 1.5rem 2rem; border-radius: 14px; background: #111827; box-shadow: 0 8px 30px rgba(0,0,0,.2), inset 0 1px 0 rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); }
      h1 { margin: 0 0 .5rem; font-size: 1.25rem; color: #a5b4fc; }
      p { margin: .25rem 0; color: #cbd5e1; }
      code { background: #0b1220; padding: .2rem .4rem; border-radius: 6px; color: #93c5fd; }
    </style>
  </head>
  <body>
    <section class="card">
      <h1>CCS API backend</h1>
      <p>Backend ini hanya menyediakan REST API. Tidak ada UI yang dirender dari Laravel.</p>
      <p>Gunakan endpoint API di path <code>/api/*</code> dari aplikasi frontend Anda.</p>
    </section>
  </body>
</html>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>
