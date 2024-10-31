<form action="options.php" method="post">
    <?php
    settings_fields('reviewnow_plugin_options');
    do_settings_sections('reviewnow_plugin');
    ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
</form>
