-- add editor object
INSERT INTO `sys_objects_editor` VALUES (NULL, 'sys_tinymce4', 'TinyMCE4', 'default', 'BxTemplEditorTinyMCE4', '');

-- set default editor to TinyMCE 4
UPDATE `sys_options` SET `VALUE` = 'sys_tinymce4' WHERE `Name` = 'sys_editor_default';
