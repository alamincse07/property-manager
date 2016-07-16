<?php
/**
 * Created by PhpStorm.
 * User: Raaz Ahmed
 * Date: 6/15/14
 * Time: 11:39 PM
 */
?>
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
        <h1>Import Data</h1>

        <?php if ($this->session->flashdata('message')) { ?>
            <div class="alert alert-success">
                <?php echo $this->session->flashdata('message') ?>
            </div>
        <?php } ?>

        <div>
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a data-toggle="tab" href="#selections">Selections</a></li>
                <li class=""><a data-toggle="tab" href="#drivers">Drivers</a></li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div id="selections" class="tab-pane fade active in">
                    <div class="row-fluid">
                        <div class="mini-layout-body" style="text-align:center; display: none;">
                            <div class="alert fade in">
                                <button class="close" data-dismiss="alert" type="button">×</button>
                                <div class="js_ajax_msg"></div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input class="btn btn-info" type="button" id="add-btn" value="Add New"/>
                    </div>
                    <!--                style="display: none;"-->
                    <!--                <form class="export-form">-->
                    <div style="height:5px;"></div>
                    <!--      -->
                    <div class="export-add" style="display: none;">
                        <div class="control-group">
                            <label class="control-label" for="driver">Driver</label>
                            <select class="span3 js_driver">
                                <?php
                                $driver_list = $this->data['driver_list'];
                                foreach ($driver_list as $single_name) {
                                    ?>
                                    <option
                                        value="<?php echo strtolower('.' . $single_name) ?>"><?php echo strtoupper($single_name) ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="module">Module</label>
                            <select class="span3 js_module">
                                <option value="">Select One</option>
                                <?php
                                $module_list = $this->data['module_list'];
                                foreach ($module_list as $single_name) {
                                    ?>
                                    <option
                                        value="<?php echo strtolower($single_name) ?>"><?php echo ucfirst($single_name) ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="control-group">
                            <label class="">
                                Use First Row <input type="checkbox" checked>
                            </label>
                            <label class="control-label" for="name">Delimiter</label>
                            <input type="text" class="input-block-level span3" value=";"/>
                            <label class="control-label" for="name">Encloser</label>
                            <input type="text" class="input-block-level span3" value="''"/>
                            <label class="control-label" for="name">File</label>
                            <input type="file" class="input-block-level span3"/>
                        </div>
                        <p class="form-row">
                            <input type="button" class="btn btn-info import-create-btn" value="Create"/>
                            <input type="button" class="btn btn-info import-save-btn" value="Save"
                                   style="display: none"/>
                        </p>
                    </div>
                    <!--                </form>-->
                    <table class="table table-striped table-bordered table-hover table-primary export-view">
                        <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Module</th>
                            <th style="text-align:center">Total</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div id="drivers" class="tab-pane fade">
                    <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.
                        Exercitation
                        +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko
                        farm-to-table
                        craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts
                        ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus
                        mollit.
                        Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack
                        odio
                        cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson
                        biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus
                        tattooed
                        echo park.</p>
                </div>
            </div>
        </div>
    </div><!--/content-->
<?php echo $footer ?>