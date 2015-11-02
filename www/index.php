<?php

include('variables.php');
include('functions.php');
date_default_timezone_set('Australia/Melbourne');

?>
<html>
<head>
  <title></title>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <link rel='stylesheet' type='text/css' href='//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
</head>
<body>
  <div class="container" style="width:1370px">
    <div class="row">
      <div class="col-md-12">
        <h1>Drush Helper Dashboard</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9"></div>
      <div class="col-md-3">
        <form action="index.php" method="get">
          <input type="hidden" name="action" value="update_prod_list" />
          <input type="submit" class="btn btn-success" value="Update Sites" />
        </form>
      </div>
    </div>
<?php if($message != null) { ?>
  <div class="row">
    <div class="alert alert-info" role="alert">
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
      <?php echo $message; ?>
    </div>
  </div>
<?php } ?>
  <div class="row">
    <div class="col-md-12">
      <h2>Aliases</h2>
    </div>
  </div>
  <?php
  $i = 0;
  $data = file_get_contents ('../drush/aliases.json');
  $json = json_decode($data);
  $added = array();
  usort($json, 'cmp');
  $sites = file_get_contents ('../drush/sites.json');
  $sites_json_data = json_decode($sites);
  foreach ($json as $key => $value) { ?>
    <?php
      if(in_array($value->domain, $added)) {
        continue;
      }
      $added[] = $value->domain;
    ?>
    <?php if ($i%2==0) { // if counter is multiple of 3 ?>
      <div class="row">
    <?php } ?>

    <div class="col-md-6">
      <div class="well">
          <h4><?php echo $value->domain; ?></h4>
          <a href="/index.php?action=login&alias=<?php echo $value->domain; ?>" class="btn btn-success">Login (uli)</a>
          <a href="/index.php?action=status&alias=<?php echo $value->domain; ?>" class="btn btn-info">Status (status)</a>
        <a href="/index.php?action=clear&alias=<?php echo $value->domain; ?>" class="btn btn-danger">Clear Caches (cc all)</a>
        <br />
        <h5>Sample commands</h5>
        <p>drush @<?php echo $value->domain ?> ssh</p>
        <p>drush rsync $LOCAL_FILE @<?php echo $value->domain; ?>:$REMOTE_FILE_LOCATION </p>
        <div class="well">
          <?php
            foreach($sites_json_data as $value_site) {
              if($value->id == $value_site->id) {
                ?>
                <dl class="dl-horizontal">
                  <dt>Owner</dt>
                  <dd><?= $value_site->owner ?></dd>
                  <dt>Site</dt>
                  <dd><?= $value_site->site ?></dd>
                  <dt>Created</dt>
                  <dd><?= date('r', $value_site->created); ?> (<?= $value_site->created ?>)</dd>
                  <dt>Domains</dt>
                  <dd>
                    <?php
                    foreach($value_site->domains as $domain) {
                      ?>
                        <a href="https://<?= $domain ?>" target="_blank"><?= $domain ?></a><br />
                      <?php
                    }
                    ?>
                  </dd>
                </dl>
          <?php
                break;
              }
            }
          ?>
        </div>
      </div>
    </div>

    <?php $i++;

    if($i%2==0) { // if counter is multiple of 3 ?>
      </div>
    <?php }
  }
  ?>
  </div>
</body>
</html>

