<?php
/**
 * Goals
 *
 * This template can be overridden by copying it to yourtheme/upsellwp-mini-cart/style-1/components/goals.php.
 *
 * HOWEVER, on occasion we will need to update template files and you (the theme developer) will need to copy the new files
 * to your theme to maintain compatibility. We try to do this as little as possible, but it does happen.
 */
defined('ABSPATH') || exit;

if (empty($advanced) || empty($style)) {
    return;
}
$goals = array_filter($advanced['goals']['list'] ?? []);
if (empty($goals)) {
    return;
}
?>
<div class="uwpmc-goals"
     style="<?php echo(!empty($advanced['goals']['enable']) ? 'display: block;' : 'display: none;'); ?>">
    <?php foreach ($goals as $key => $goal) { ?>
        <div class="uwpmc-goal uwpmc-<?php echo esc_attr(str_replace('_', '-', $key)); ?>"
             style="border-bottom: 0.5px solid #e6e6e6; line-height: 0; text-align: center; <?php echo(empty($goal) ? 'display: none;' : 'display: block;'); ?>
             <?php echo !empty($style['goals']) ? esc_attr($style['goals']) : ''; ?>">
            <span class="uwpmc-goal-text"><?php echo wp_kses_post($goal['message'] ?? ''); ?></span>
            <input class="uwpmc-goal-range" type="range" min="0"
                   max="<?php echo !empty($goal['target']) ? esc_attr($goal['target']) : ''; ?>"
                   style="<?php echo !empty($style['goals']) ? esc_attr($style['goals']) : ''; ?>"
                   value="<?php echo !empty($goal['current']) ? esc_attr($goal['current']) : ''; ?>"
                   disabled/>
        </div>
    <?php } ?>
</div>