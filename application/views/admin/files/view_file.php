<?php echo $header; ?>
<?php echo $sidebar; ?>

    <div class="span10 content">

        <?php echo $hugemenu; ?>

        <?php
        if (!empty($file_details)) {

            foreach ($file_details as $key => $file) {
                ?>
                <ul class="thumbnails">
                    <li class="span4">
                        <a href="#" class="thumbnail">
                            <img alt="Preview" style="width: 360px; height: 270px;"
                                 src="<?= base_url() ?>assets/default/img/preview.png">
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url() ?>admin/files/download/<?= $file['file_id'] ?>" class="thumbnail">

                            <button class="btn btn-app btn-purple">
                                <i class="icon-cloud-download"></i>
                                <?= lang('download') ?>
                            </button>
                        </a>
                    </li>
                    <?php
                    if ($this->session->userdata('level') >= 4) {
                        ?>
                        <li>
                            <a class="thumbnail">
                                <button class="btn btn-app btn-warning" id="bootbox-confirm">
                                    <i class="icon-trash"></i>
                                    <?= lang('delete') ?>
                                </button>
                            </a>
                        </li>
                    <?php } ?>


                    <li class="span4">
                        <h3 class="header smaller lighter red"><i
                                class="icon-info-sign orange"></i><?= lang('file') ?> <?= lang('details') ?></h3>
                        <ul class="unstyled">
                            <li><i class="icon-lightbulb red"></i> <?= lang('file') ?> <?= lang('name') ?>:
                                <strong><?= $file['name'] ?></strong></li>
                            <li><i class="icon-star blue"></i> <?= lang('file') ?> <?= lang('size') ?>:
          	<span class="badge badge-success"><?php
                if (!empty($file['file'])) {
                    echo round(filesize('uploads/' . $file['file']) / 1024, 2) . ' KB';
                } else {
                    echo '0 KB';
                }
                ?>
          	</span></li>
                            <li><i class="icon-user red"></i> <?= lang('uploaded_by') ?>:
                                <span class="badge badge-inverse"><?= $file['username'] ?></span></li>
                            <li><i class="icon-time green"></i> <?= lang('upload') ?> <?= lang('date') ?>
                                : <?= $file['upload_time'] ?></li>
                            <li><i class="icon-lock red"></i> <?= lang('private') ?>: <span
                                    class="badge badge-info"><?= $file['private'] ? 'Yes' : 'No' ?></span></li>
                            <li><i class="icon-money green"></i> <?= lang('memberships') ?> : <span
                                    class="badge badge-inverse"><?php
                                    foreach (unserialize($file['packages']) as $key => $value) {
                                        $this->db->where('m_id', $value);
                                        $this->db->select('membership');
                                        $query = $this->db->get('fx_memberships');
                                        foreach ($query->result() as $row) {
                                            $package_name = $row->membership;
                                        }
                                        echo "<ul>";
                                        echo '<li>' . $package_name . '</li>';
                                        echo "</ul>";
                                    }
                                    ?>
                                
					</span></li>
                            <li><i class="icon-pushpin green"></i> <?= lang('notes') ?>: <?= $file['notes'] ?></li>

                        </ul>
                    </li>

                </ul>
                <a href="<?= site_url() ?>admin/files/<?php
                if ($this->session->userdata('level') == 1) {
                    echo "user_files";
                }
                ?>">
                    <button class="btn btn-info">
                        <i class="icon-folder-open"></i>
                        <?= lang('files') ?>
                    </button>
                </a>
            <?php } ?>

        <?php
        } else {

        }
        ?>

        <?php echo $pagination['links']; ?>

    </div> <!-- .content -->

<?php echo $footer; ?>