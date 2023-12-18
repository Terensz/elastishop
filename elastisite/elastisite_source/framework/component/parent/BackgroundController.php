<?php
namespace framework\component\parent;

use framework\component\parent\Rendering;

class BackgroundController extends Rendering
{
    public function renderBackground($viewFilePath, $viewData)
	{
        $widget = $this->renderView($viewFilePath, $viewData);
        return $widget;
	}
}
