<style type="text/css">
    .status {
        width: 10px;
        height: 10px;
        cursor: pointer;
        border-radius: 100% !important;
        background-color: green;
    }

    .de-activate {
        background-color: gray;
    }

    .grid_height {
        min-height: 400px !important;
    }

    .edit_td > span {
        font: 14px arial;
    }
</style>
<?php
echo $header;
echo $sidebar;
?>

<div class="span10 content grid_height">

    <?php echo $hugemenu ?>
    <h1>Export your property listing</h1>

    <?php if ($this->session->flashdata('message')) { ?>
        <div class="alert alert-success">
            <?php echo $this->session->flashdata('message') ?>
        </div>
    <?php } ?>

    <div>
        <a data-bb="confirm12" role="button" href="javascript:void(0)" title="Export" data-toggle="tooltip" data-file="<?=$this->session->userdata('email')?>" data-file-type=".xml" class="js_export">Click here to export or download <i class="icon-play"></i></a>
    </div>
</div><!--/content-->
<?php echo $footer ?>
<script type="text/javascript">
    function getXml() {
        window.location.href = "exportXml";
    }

    function getCsv() {
        window.location.href = "exportCsv";
    }
    $(document).ready(function(){
    $('.js_export').click(function(){
        var file_name= $(this).attr('data-file');
        var file_type= 'xml';
        var type= file_type.replace('.','');
        type=type.toLowerCase();

        $.ajax({
            url:BASE_URL+"admin/GetAllPropertyData?file_type="+file_type+"&feed="+file_name
            ,data:{type:type}
            ,success: function(data) {

                console.log(BASE_URL+"admin/exportproperty?file_type="+file_type+"&feed="+file_name);
                /*window.location = BASE_URL+"admin/exportproperty?file_type="+file_type+"&feed="+file_name;*/
            }
            ,error:
                function()
                {

                    console.log("ajax error");
                }
        });
    });
    });
</script>






