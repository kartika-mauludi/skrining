<!-- tambah data -->

<div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="adddata" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Tambah Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="{{ route('masterperingkat.store') }}" method="POST" id="addperingkat" data-target-table="#tbl-rank">
                @csrf
                <div class="modal-body">
                    <label for=""> Rank </label>
                    <input type="text" name="peringkat" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update data -->

<div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="editData" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Edit Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form id="formeditperingkat" data-target-table="#tbl-rank">
                @csrf
                 @method('PUT')
                <input type="hidden" id="editID">
                <div class="modal-body">
                    <label for=""> Rank </label>
                    <input type="text" name="peringkat" id="peringkat" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ====================================== periode ==================================== -->

<!-- tambah data -->

<div class="modal fade" id="addDataPeriode" tabindex="-1" role="dialog" aria-labelledby="adddataPeriode" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Tambah Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form action="{{ route('periode.store') }}" method="POST" id="addperiode" data-target-table="#tbl-periode">
                @csrf
                <div class="modal-body">
                    <label for=""> Periode </label>
                    <input type="text" name="periode" class="form-control">
                </div>
                <div class="modal-body">
                    <label for=""> Edisi </label>
                    <input type="text" name="edisi" id="edisi" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- update data -->

<div class="modal fade" id="editDataPeriode" tabindex="-1" role="dialog" aria-labelledby="editDataPeriode" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal"> Edit Data </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form id="formeditPeriode" data-target-table="#tbl-periode">
                @csrf
                 @method('PUT')
                <input type="hidden" id="editIDPeriode">
                <div class="modal-body">
                    <label for=""> Periode </label>
                    <input type="text" name="periode" id="periode" class="form-control" required>
                </div>
                <div class="modal-body">
                    <label for=""> Edisi </label>
                    <input type="text" name="edisi" id="edisi" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-primary" > Save Changes </button>
                </div>
            </form>
        </div>
    </div>
</div>

