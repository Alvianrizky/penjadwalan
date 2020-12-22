<div class="mx-3">
    <div class="table-responsive mt-4">
        <!-- <div id="hasil"></div> -->
        <?php echo $hasil; ?>
    </div>
</div>

<div class="card bg-primary text-white text-center mx-5 col-3">
    <div class="card-body">
        <p class="card-text">Statistik</p>
        <p class="card-text">SI-7, SI-5</p>
        <p class="card-text">Dedy Ardiansyah, S.Sos., M.AB</p>
    </div>
</div>

<button type="button" id="cek" class="btn btn-primary">Cek</button>

<script type="text/javascript">
    $('#cek').click(function() {
        Swal.fire({
            icon: 'success',
            title: 'Your work has been saved',
            showConfirmButton: false,
            timer: 1500
        });
    })
</script>