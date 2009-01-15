<html>
<head>
  <title>Bookmarks for <?php print filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS); ?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<table >
  <thead><th>Count</th><th>URL</th><th>Descriptions</th></thead>
  <tbody>
  <?php foreach ($urls as $url => $score): if ($score < 3) continue; ?>
    <tr>
      <td><?php print filter_var($score, FILTER_SANITIZE_NUMBER_INT); ?></td>
      <td style="width:30%"><a href="<?php print filter_var($url, FILTER_SANITIZE_SPECIAL_CHARS); ?>"><?php print filter_var($meta[$url]['title'], FILTER_SANITIZE_SPECIAL_CHARS); ?></a></td>
      <td>
      <?php if (!empty($meta[$url]['descriptions'])): foreach ($meta[$url]['descriptions'] as $user => $text): ?>
        <div class="quote"><span class="user"><?php print filter_var($user, FILTER_SANITIZE_SPECIAL_CHARS); ?></span>: <span class="description"><?php print filter_var($text, FILTER_SANITIZE_STRING); ?></span></div>
      <?php endforeach; endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</body>
</html>