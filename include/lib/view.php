<?php
/**
 * 视图控制
 * @copyright (c) Emlog All Rights Reserved
 */

class View {
	public static function getView($template, $ext = '.php') {
		if (!is_dir(TEMPLATE_PATH)) {
			jcMsg('当前使用的模板已被删除或损坏，请登录后台更换其他模板。', SYS_URL);
		}
		return TEMPLATE_PATH . $template . $ext;
	}	
}
