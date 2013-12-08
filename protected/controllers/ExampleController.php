<?php

/**
 * Description of ExampleController
 *
 * @author satitseethaphon
 */
class ExampleController extends Controller {

    public function filters() {
        return array(
            'rights',
        );
    }

    public function allowedActions() {
        return 'index';
    }

    public function actionIndex() {
        $this->render('index');
    }

    public function actionHeigchart() {
        $this->render('heigchart');
    }

    public function actionMpdf() {
        # mPDF
        $mPDF1 = Yii::app()->ePdf->mpdf();

        # You can easily override default constructor's params
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');

        # render (full page)
        $mPDF1->WriteHTML($this->render('index', array(), true));

        # Load a stylesheet
        $stylesheet = file_get_contents(Yii::getPathOfAlias('webroot.css') . '/main.css');
        $mPDF1->WriteHTML($stylesheet, 1);

        # renderPartial (only 'view' of current controller)
        $mPDF1->WriteHTML($this->renderPartial('mpdf', array(), true));

        # Renders image
        $mPDF1->WriteHTML(CHtml::image(Yii::getPathOfAlias('webroot.css') . '/bg.gif'));

        # Outputs ready PDF
        $mPDF1->Output();
    }
    
    public function actionNode()
    {
        Yii::app()->nodeSocket->registerClientScripts();
        $this->render('node');
    }
    public function actionNode2()
    {
        Yii::app()->nodeSocket->registerClientScripts();
        $this->render('node2');
    }

}
