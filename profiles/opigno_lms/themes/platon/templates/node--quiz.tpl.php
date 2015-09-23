<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php print render($title_prefix); ?>
  <?php if (!$page): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php if ($display_submitted): ?>
    <div class="submitted">
      <?php print $submitted; ?>
    </div>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <table class="quiz-stats-table">
      <thead>
        <tr>
          <th><?php print $title; ?></th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php $rows = 5; $rendered_take = FALSE; ?>
        <?php if (isset($quiz_type[LANGUAGE_NONE][0]['value'])): ?>
          <?php $rows++; $rendered_take = TRUE; ?>
          <tr>
            <td class="vertical-header">
              <?php print t("Type"); ?>:
            </td>
            <td>
              <?php switch($quiz_type[LANGUAGE_NONE][0]['value']) {
                case 'quiz':
                  print t("Quiz");
                  break;

                case 'theory':
                  print t("Theory");
                  break;

                default:
                  print t("Mixed");
                  break;
              } ?>
            </td>
            <td class="take-button-cell" rowspan="<?php print $rows; ?>">
              <?php print render($content['take']); ?>
              <?php if (!$page): ?>
                <?php print l(t("Read more"), "node/{$node->nid}", array('attributes' => array('class' => array('read-more', 'action-element', 'action-sort-element')))); ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endif; ?>
        <tr>
          <td class="vertical-header">
            <?php print t("Questions"); ?>:
          </td>
          <td>
            <?php print $node->number_of_random_questions + _quiz_get_num_always_questions($node->vid); ?>
          </td>
        </tr>
        <tr>
          <td class="vertical-header">
            <?php print t("Attempts allowed"); ?>:
          </td>
          <td>
            <?php print $node->takes == 0 ? t('Unlimited') : $node->takes; ?>
          </td>
        </tr>
        <tr>
          <td class="vertical-header">
            <?php print t("Available"); ?>:
          </td>
          <td>
            <?php if ($node->quiz_always): ?>
              <?php print t('Always'); ?>
            <?php else: ?>
              <div><?php print t('Opens'); ?> <?php print format_date($node->quiz_open, 'short'); ?></div>
              <div><?php print t('Closes'); ?> <?php print format_date($node->quiz_close, 'short'); ?></div>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="vertical-header">
            <?php print t("Pass rate"); ?>:
          </td>
          <td>
            <?php print  $node->pass_rate; ?>%
          </td>
        </tr>
        <tr>
          <td class="vertical-header">
            <?php print t("Backwards navigation"); ?>:
          </td>
          <td>
            <?php print $node->backwards_navigation ? t('Allowed') : t('Forbidden'); ?>
          </td>
          <?php if (!$rendered_take): ?>
            <td class="take-button-cell" rowspan="<?php print $rows; ?>">
              <?php print render($content['take']); ?>
              <?php if (!$page): ?>
                <?php print l(t("Read more"), "node/{$node->nid}", array('attributes' => array('class' => array('read-more', 'action-element', 'action-sort-element')))); ?>
              <?php endif; ?>
            </td>
          <?php endif; ?>
        </tr>
      </tbody>
    </table>

    <?php if ($page): ?>
      <?php
        // We hide the comments and links now so that we can render them later.
        hide($content['comments']);
        hide($content['links']);
        hide($content['stats']);
        print render($content);
      ?>
    <?php endif; ?>
  </div>

  <?php print render($content['comments']); ?>

</div>
