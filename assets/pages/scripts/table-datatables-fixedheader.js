var TableDatatablesFixedHeader = function () {
      function updateRanking(dataId,dataRank){
            $.ajax({
                url: "ranking.php",
                type: "POST",
                data: {dataId:dataId,dataRank:dataRank},
                dataType: "jsonp",
                success: function(data){
                    
                }
                ,
                error: function(){
                }
            });
        }
    var initTable1 = function () {
        var darg=false;
        var tb_sample =$('#sample_1');
        if(typeof tb_sample.attr("data-rankDarg") !== 'undefined')
            darg='td:not(:first-child,.nodarg)';
        if(tb_sample.data('nobuttons'))
                buttons = [
                        ];
            else
                buttons = [
                        {
                            text: 'Select All',
                            className: 'btn dark btn-outline pull-left',
                            action: function () {
                                this.rows().select();
                            }
                        },
                        {
                            text: 'Select None',
                            className: 'btn dark btn-outline pull-left',
                            action: function () {
                                this.rows().deselect();
                            }
                        },
                        {
                            extend: 'selected',
                            text: 'Delete Selected Item(s)',
                            className: 'btn Red btn-outline pull-left bt-delete-all',
                            action: function () { 
                                    bootbox.confirm("Are you sure to delete "+this.rows(".selected")[0].length+" item?",function(result) {
                                             if(result){
                                             App.blockUI({
                                                boxed: true
                                            });

                                            window.setTimeout(function() {
                                                del_select(result);
                                                App.unblockUI();
                                            }, 2000);
                                             return true;
                                            }}
                                    );
                                
                            }
                        },
                        { extend: 'colvis', className: 'btn dark btn-outline pull-right', text: 'Columns'},
                        { extend: 'csv', className: 'btn purple btn-outline  pull-right' },
                        { extend: 'excel', className: 'btn yellow btn-outline pull-right' },
                        { extend: 'pdf', className: 'btn green btn-outline pull-right' },
                        { extend: 'copy', className: 'btn red btn-outline pull-right' },
                        { extend: 'print', className: 'btn dark btn-outline pull-right' }
                        ];
        var table = $('#sample_1').DataTable( {

            "columnDefs": [ {
                "targets"  : 'no-sort',
                "orderable": false,
            }],
            "rowReorder": {
            selector: darg
            },
                "select": {
                     
                    "style": 'multi',
                    "selector": 'td:first-child',
                    "items": 'row',
                    "blurable": true
                },
                buttons: buttons,
            
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 50,
            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    } );
 if(typeof $('#sample_1').attr("data-rankDarg") !== 'undefined')
    table.on( 'row-reorder', function ( e, diff, edit ) {
         // console.log(diff);
        var dataId=[],dataRank=[];var id, pos;
        if (!$.isEmptyObject(diff)){
            for(var key in diff){
                // console.log(diff[diff[key].oldPosition].node.id+" : rank = "+diff[diff[key].newPosition].node.id );
                var itemNew=-1;
                for(var key2 in diff){
                    if(diff[key2].oldPosition == diff[key].newPosition)
                        itemNew=key2;
                }
                // console.log('id : '+diff[key].node.attributes['data-id'].nodeValue+' rank = '+diff[itemNew].node.attributes['data-rank'].nodeValue );
                // console.log('oldPosition : '+diff[key].oldPosition+' newPosition : '+diff[key].newPosition+' rank : '+diff[key].node.attributes['data-rank'].nodeValue);
                dataId[key] = diff[key].node.attributes['data-id'].nodeValue;
                dataRank[key] = diff[itemNew].node.attributes['data-rank'].nodeValue;
            }
            // console.log(data);
            updateRanking(dataId,dataRank);
            for(var key in diff){
                diff[key].node.attributes['data-rank'].nodeValue = dataRank[key];
            }
        }

 
    } );
    $.getScript("../assets/global/plugins/bootbox/bootbox.min.js");
    $.getScript("../assets/global/plugins/jquery.blockui.min.js");
    function del_select(result){
             if(result){
                                var rows = tb_sample.find('tr.selected');
                               
                                rows.each(function(){
                                    var row = $(this);
                                    var bt_delete = $(row.find('#bs_confirmation_delete')[0]);
                                    var delete_id = bt_delete.attr('data-val');
                                    // $.get("delete.php",{id:delete_id},function(){
                                    //     row.remove();
                                    // });
                                    $.ajax({
                                            url: 'delete.php?id='+delete_id,
                                            success: function (result) {
                                                row.remove();
                                            },
                                            async: false
                                        });
                                }); 
                                tb_sample.DataTable().rows('.selected').deselect();
                                
                                }
        }
    }

    var initTable2 = function () {
        var table = $('#sample_2');

        var fixedHeaderOffset = 0;
        if (App.getViewPort().width < App.getResponsiveBreakpoint('md')) {
            if ($('.page-header').hasClass('page-header-fixed-mobile')) {
                fixedHeaderOffset = $('.page-header').outerHeight(true);
            } 
        } else if ($('.page-header').hasClass('navbar-fixed-top')) {
            fixedHeaderOffset = $('.page-header').outerHeight(true);
        }

        var oTable = table.dataTable({

            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup rowreorder extension: http://datatables.net/extensions/fixedheader/
            fixedHeader: {
                header: true,
                footer: true,
                headerOffset: fixedHeaderOffset
            },

            "order": [
                [0, 'asc']
            ],
            
            "lengthMenu": [
                [5, 10, 15, 30, -1],
                [5, 10, 15, 30, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 30,
            
            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
      });
    }

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            initTable1();
            initTable2();
        }

    };

}();

jQuery(document).ready(function() {
    TableDatatablesFixedHeader.init();
});