<?php
function smarty_function_faqmulti_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
	$faqmulti = new plugins_faqmulti_public($modelTemplate);
	$assign = isset($params['assign']) ? $params['assign'] : 'faqmulti';
	$smarty->assign($assign,$faqmulti->getfaqmultis($params));
}