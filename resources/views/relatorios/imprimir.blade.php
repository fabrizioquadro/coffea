@extends('layout.imprimir')

@section('conteudo')
<style media="print">
    *{
        padding: 0px !important;
        margin: 0px !important;
    }
    td, th{
        font-size: 8px !important;
    }
</style>
<div class="row pt-3 pb-3 mb-2">
    <div class="col-4" style="border: 1px solid #cdcdcd; border-radius: 10px; height: 100%" align='center'>
        <img src="/public/img/Supporto_Alta.png" class="img-fluid" alt='logo'>
    </div>
</div>
{!! $dados !!}
<script>
window.addEventListener('load', ()=>{
    print();
})

window.addEventListener('afterprint', ()=>{
    window.close();
})
</script>
@endsection
