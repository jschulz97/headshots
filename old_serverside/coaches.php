<!--Header-->
<?php include "sv_header.html"; ?>


<!--NAV BAR-->
<?php include "sv_nav.html"; ?>


<style>

    .hideable-btn {
        display: none;
    }

    input[type='number']{
        width: 60px;
    } 

</style>

<script>

    $('document').ready(function() {
        var current_delete_id;

        $('#team_id_submit').click(function(e) {
            e.preventDefault();
            var team_id = $('#team_id').val();
            populate_table('http://node.cams.schulzvideo.com/get_coaches_by_team_id?team_id='+team_id, $('#table-div'),
                ['ID','First Name','Last Name','Title','Short Title','Priority','Headshot URL','!delete','!publish'],
                ['id','f_name','l_name','title','short_title','priority','headshot']);
            
            create_add_coach_table($('#add_coach_div'),
                ['Team ID','First Name','Last Name','Title','Short Title','Priority','Headshot URL'],
                ['team_id','f_name','l_name','title','short_title','priority','headshot'],
                [team_id,'','','','','0',''])

            $('.card-footer').css('display','block');
        });

        
        $('#add-new-coach').click(function(e) {
            e.preventDefault();
            var form_data = $('#form-add-new-coach').serialize();
            
            $.get('http://node.cams.schulzvideo.com/insert_coach?'+form_data, function(data, status) {
                console.log(status)
                $('#success').css('display','block')
            });
        });


        $('#table-div').on('click', '.toggle-visibility', function(e){
            e.preventDefault();
            
           if($('.visibility-icon').text() == 'visibility_off') { 
                $('.visibility-icon').html('visibility');
                $('.hideable-btn').css('display','block');
            } else {
                $('.visibility-icon').html('visibility_off');
                $('.hideable-btn').css('display','none');
            }
        });


        $('#table-div').on('click', '.delete', function(e){
            console.log('delete: '+ this.id + '...');
            current_delete_id = this.id;
            $('#confirmation-name').text('Delete '+this.id+'?');
            $('#confirm-delete').modal('show');
        }); 


        $('#confirm-delete-btn').click(function(e) {
            console.log(current_delete_id);
            $.get('http://node.cams.schulzvideo.com/delete_coach?id='+current_delete_id, function(data, status) {
                console.log('Done.')
            });
            $('#confirm-delete').modal('hide');
        });


        $('#table-div').on('click', '.publish', function(e){
            e.preventDefault();
            console.log('publish: '+ this.id + '...');
            var form_data = $('#form-id-'+this.id).serialize();

            $.get('http://node.cams.schulzvideo.com/update_coach?id='+this.id+'&'+form_data, function(data, status) {
                console.log('Done.')
            });
        });



        function pretty_date(date) {
            date_str = new Date(date).toUTCString();
            arr = date_str.split(' '); 
            return arr[0]+' '+arr[1]+' '+arr[2]+' '+arr[3];
        }


        function populate_table(uri, parent_div, col_labels, col_names) {
            $.get(uri, function(data, status) {
                var result = JSON.parse(data);

                // build dropdown 
                var built_html = `<table class='table'><thead><tr>`;
                for(var i=0; i<col_labels.length; i++) {
                    if(col_labels[i][0] != '!'){built_html += '<th>' + col_labels[i] + '</th>';} 
                    else if(col_labels[i] == '!publish') {built_html += '<th><button  class="btn btn-secondary toggle-visibility" ><span id="" class="material-icons visibility-icon">visibility_off</span></button></th>'}
                    else {built_html += '<th></th>';}
                }
                built_html += `</tr></thead><tbody>`;
                for(var i=0; i<result.length; i++) {
                    var id = result[i]['id'];
                    built_html += '<tr><form id="form-id-'+id+'"></form>'
                    for(var key in col_labels) {
                        var val = 'none';

                        if(col_labels[key][0] != '!') {
                        // input field
                            val = result[i][col_names[key]];
                            if(col_labels[key] == 'ID') {
                                built_html += '<td>' + val + '</td>';
                            } else if(col_names[key] == 'priority') {
                                built_html += '<td><input name="'+col_names[key]+'" form="form-id-'+id+'" type="number" value="' + val + '"/></td>';
                            }
                            else {
                                built_html += '<td><input name="'+col_names[key]+'" form="form-id-'+id+'" type="text" value="' + val + '"/></td>';
                            }                        
                        } else
                        // button
                        {
                            if(col_labels[key] == '!delete') {
                                built_html += '<td><button id="'+result[i]['id']+'" class="btn btn-danger hideable-btn delete" ><span class="material-icons">delete_forever</span></button></td>'
                            } 
                            else if(col_labels[key] == '!publish') {
                                built_html += '<td><button id="'+result[i]['id']+'" class="btn btn-primary hideable-btn publish"><span class="material-icons">publish</span></button></td>'
                            }
                        }
                        
                    }
                    built_html += '</tr>';
                }
                built_html += '</tbody></table>';

                // apply to dropdown on page
                parent_div.html(built_html);
            });
        }


        function create_add_coach_table(parent_div, col_labels, col_names, default_vals) {

            // build dropdown 
            var built_html = `<table class='table'><thead><tr>`;
            for(var i=0; i<col_labels.length; i++) {
                built_html += '<th>' + col_labels[i] + '</th>';
            }
            built_html += `</tr></thead><tbody>`;

            built_html += '<tr>'
            for(var key in col_labels) {
                
                if(default_vals[key].length != 0) {
                    built_html += '<td><input type="number" name="'+col_names[key]+'" value="' + default_vals[key] + '" / ></td>';
                } else if(col_names[key] == 'priority') {
                    built_html += '<td><input type="number" name="' +col_names[key]+ '"/></td>';
                } else {
                    built_html += '<td><input type="text" name="' +col_names[key]+ '"/></td>';
                }
            }
            built_html += '</tr>'
        
            built_html += '</tbody></table>'

            // apply to dropdown on page
            parent_div.html(built_html);

        }


    });

    
</script>


<!--BODY-->
<body>
    <div class="row">
        <!-- <div class="col-lg-2"></div> -->
        <div class="col-lg-12">

            <br>
            <h1 class='text-center'>Edit Coaches</h1>

            <br>
            <br>

            <button  class="btn btn-secondary" id="visibility-icon-btn" style="display: none;"><span id="visibility-icon" class="material-icons">visibility_off</span></button>

            <div class='card' id=''>
                <div class='card-header'>
                    <form><table><tr><td><h5>Team ID: &nbsp</h5></td><td><input type='number' id='team_id'>&nbsp</td> 
                    <td><button id='team_id_submit' class='btn btn-secondary'>Submit</button></td>
                    </tr></table></form>
                </div>
                <div class='card-body' id='table-div' style='overflow-y:auto'>
                   
                </div>
                <div class='card-footer' style='display: none;'>
                    <div style='overflow-y:auto'>
                        <form id="form-add-new-coach"><div  id='add_coach_div'></div></form>
                        <form id=''><button id='add-new-coach' class='btn btn-secondary'>Add New Coach</button></form>
               
                    </div>
            
                </div>
            </div>
        

        </div>
        <!-- <div class="col-lg-2"></div> -->
    </div>




    <!-- The Modal -->
    <div class="modal" id="confirm-delete">
    <div class="modal-dialog">
        <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Confirm Delete</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <h5 id='confirmation-name'></h5>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" id='confirm-delete-btn'>Delete</button>
        </div>

        </div>
    </div>
    </div>

</body>

</html>