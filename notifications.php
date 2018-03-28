<?php
include("inc/header.php");
?>

<section id="main">
    <section id="content">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <!-- Recent Items -->
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $lang["notifications"];?> <small><?php echo $lang["notifications"];?></small></h2>
                        </div>

                        <div class="card-body m-t-0">
                            <table class="table table-inner table-vmiddle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo $lang["notification"];?></th>
                                    <th><?php echo $lang["date"];?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $dbh->prepare("select * from notifications order by id desc limit 200");
                                $stmt->execute();
                                $i = 0;
                                while ($row = $stmt->fetch()) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $row['text'];?></td>
                                        <td><?php echo $row['date'];?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<?php
include("inc/footer.php");
?>
