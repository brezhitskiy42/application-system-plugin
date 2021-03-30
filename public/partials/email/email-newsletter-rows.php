<?php
// Если файл вызван напрямую, прерываем работу
if ( ! defined( 'ABSPATH' ) ) {
  die;
}
?>

<table style="margin:0 auto;font-family:Arial,Helvetica,sans-serif;border-collapse:collapse;width:80%">
  <tbody>
    <tr style="background:#d6d6d6">
      <th style="width:60px;font-size:13px;font-weight:normal;color:#3b3b3b">Дата</th>
      <th style="font-size:13px;font-weight:normal;color:#3b3b3b">Описание заявки</th>
      <th style="width:90px;font-size:13px;font-weight:normal;color:#3b3b3b">Город</th>
    </tr>
    <?php
      $posts = get_posts( array(
        'numberposts' => 20,
        'post_type' => array( 'trade', 'rent', 'parts', 'services' )
      ) );

      foreach ( $posts as $post ):
        $date = get_the_date( 'd.m', $post->ID );
        $title = $post->post_title;
        $regions_terms = get_the_terms( $post->ID, 'regions' );
        $city = null;
        foreach ( $regions_terms as $term ) {
          if ( 0 != $term->parent ) {
            $city = $term->name;
            break;
          }
        }
        $permalink = get_permalink( $post->ID );
    ?>
      <tr style="font-size:14px;padding-left:5px">
        <td style="color:#929292;padding-top:10px;padding-bottom:10px;padding-left:5px"><?php echo $date; ?></td>
        <td style="padding-left:5px"><a href="<?php echo $permalink; ?>" target="_blank"><?php echo $title; ?></a></td>
        <td style="padding-left:5px"><?php echo $city; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>