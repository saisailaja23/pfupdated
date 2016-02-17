-- remove editor object
DELETE FROM `sys_objects_editor` WHERE `object` = 'sys_tinymce4';

-- set default editor back to TinyMCE
UPDATE `sys_options` SET `VALUE` = 'sys_tinymce' WHERE `Name` = 'sys_editor_default';
