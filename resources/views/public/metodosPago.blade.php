@extends('layouts.public')

@section('title', 'Métodos de Pago')

@section('content')
<section class="container py-5">
    <h2 class="text-center mb-5 fw-bold">💳 Métodos de Pago</h2>

    <div class="row g-4">

        {{-- 🏦 Transferencias Bancarias (Nu + Bancoppel) --}}
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body">

                    {{-- Encabezado con logos --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-primary mb-0">🏦 Transferencias Bancarias</h4>
                        <div>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/f7/Nubank_logo_2021.svg" alt="Nu" style="height:35px; margin-right:10px;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3d/Logo_de_BanCoppel.svg/250px-Logo_de_BanCoppel.svg.png" alt="Bancoppel" style="height:35px;">
                        </div>
                    </div>

                    {{-- Nu México --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary">Banco: Nu México</h6>
                        <p class="text-muted mb-1">Titular: <strong>Natael Cubedo Cornejo</strong></p>

                        <p class="mb-1 text-secondary fw-semibold">Número de Tarjeta</p>
                        <div class="input-group mb-3">
                            <input id="tarjetaNu" type="text" readonly class="form-control text-center fw-bold" value="5101 2501 2825 9015">
                            <button class="btn btn-outline-primary" onclick="copiarTexto('tarjetaNu', this)">📋</button>
                        </div>

                        <p class="mb-1 text-secondary fw-semibold">CLABE Interbancaria</p>
                        <div class="input-group">
                            <input id="clabeNu" type="text" readonly class="form-control text-center fw-bold" value="638180010147791127">
                            <button class="btn btn-outline-primary" onclick="copiarTexto('clabeNu', this)">📋</button>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Bancoppel --}}
                    <div>
                        <h6 class="fw-bold text-secondary">Banco: Bancoppel</h6>
                        <p class="text-muted mb-1">Titular: <strong>Natael Cubedo Cornejo</strong></p>

                        <p class="mb-1 text-secondary fw-semibold">Número de Tarjeta</p>
                        <div class="input-group">
                            <input id="tarjetaBancoppel" type="text" readonly class="form-control text-center fw-bold" value="4169 1608 4374 1852">
                            <button class="btn btn-outline-success" onclick="copiarTexto('tarjetaBancoppel', this)">📋</button>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-4">
                        Realiza tu transferencia y guarda tu comprobante para enviarlo como confirmación.
                    </div>
                </div>
            </div>
        </div>

        {{-- 🏪 Depósitos en OXXO (SPIN) --}}
        <div class="col-md-6">
            <div class="card shadow-lg border-0 h-100 rounded-4 overflow-hidden">
                <div class="card-body">

                    {{-- Encabezado con logo SPIN --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-danger mb-0">🏪 Depósitos en OXXO</h4>
                        <img src="https://cdn.milenio.com/uploads/media/2022/10/07/spin-respaldo-digital-femsa-division.jpg" alt="SPIN" style="height:35px;">
                    </div>

                    <p class="text-muted mb-1">SPIN by OXXO</p>
                    <p class="text-muted mb-1">Titular: <strong>Natael Cubedo Cornejo</strong></p>

                    <p class="mb-1 text-secondary fw-semibold">Número de Cuenta SPIN</p>
                    <div class="input-group">
                        <input id="clabeSpin" type="text" readonly class="form-control text-center fw-bold" value="2242 1707 6048 2483">
                        <button class="btn btn-outline-danger" onclick="copiarTexto('clabeSpin', this)">📋</button>
                    </div>

                    <div class="alert alert-warning border mt-4">
                        Llévala al cajero de OXXO y solicita un depósito a tarjeta SPIN.
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- 📌 Toast flotante --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
  <div id="toastCopiado" class="toast align-items-center text-bg-dark border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body">
        ✅ Copiado al portapapeles
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

<script>
function copiarTexto(id, btn) {
    const input = document.getElementById(id);
    navigator.clipboard.writeText(input.value).then(() => {
        const original = btn.innerHTML;
        btn.innerHTML = "✅";
        btn.classList.remove("btn-outline-primary","btn-outline-success","btn-outline-danger");
        btn.classList.add("btn-success");

        setTimeout(() => {
            btn.innerHTML = "📋";
            btn.classList.remove("btn-success");
            if(id.includes("Nu")) btn.classList.add("btn-outline-primary");
            else if(id.includes("Bancoppel")) btn.classList.add("btn-outline-success");
            else btn.classList.add("btn-outline-danger");
        }, 2000);

        const toast = new bootstrap.Toast(document.getElementById('toastCopiado'));
        toast.show();
    });
}
</script>
@endsection
