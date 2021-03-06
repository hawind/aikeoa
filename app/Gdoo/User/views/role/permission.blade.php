<style>
#grid_user_role .ag-root-wrapper {
    border: 0;
}
</style>
<div id="grid_user_role" class="ag-theme-balham" style="width:100%;height:380px;"></div>
<script>
var $table = null;
(function($) {
    var data = JSON.parse('{{json_encode($rows)}}');
    var users = JSON.parse('{{json_encode($users)}}');
    var grid = new agGridOptions();
    grid.suppressLoadingOverlay = true;
    grid.suppressNoRowsOverlay = true;
    grid.rowMultiSelectWithClick = true;
    grid.suppressRowClickSelection = false;
    grid.defaultColDef.sortable = false;
    grid.defaultColDef.filter = false;
    grid.defaultColDef.suppressMenu = true;
    grid.defaultColDef.suppressNavigable = true;
    grid.columnDefs = [
        {field:'id', hide: true},
        {width: 40, cellClass:'text-center', suppressSizeToFit: true, headerCheckboxSelection: true, checkboxSelection: true},
        {headerName: '角色编码', field:'code', cellClass:'text-center', width: 60},
        {headerName: '角色名称', field:'name', cellClass:'text-left', width: 120},
        
    ];
    var gridDiv = document.querySelector("#grid_user_role");
    new agGrid.Grid(gridDiv, grid);
    grid.api.updateRowData({add:data});

    grid.api.forEachNode(function(node) {
        if (users[node.data.id] > 0) {
            node.setSelected(true);
        }
    });
    gdoo.dialogs['user_role'] = grid;
})(jQuery);
</script>