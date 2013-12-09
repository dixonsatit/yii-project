<?php
Yii::app()->noty->noty('message', 'warning', 'top');

Yii::app()->noty->noty('message', 'alert', 'topLeft');
Yii::app()->noty->noty('message', 'error', 'topCenter');
Yii::app()->noty->noty('message', 'success', 'topRight');

Yii::app()->noty->noty('message', 'infomation', 'centerLeft');
Yii::app()->noty->noty('message', 'alert', 'center');
Yii::app()->noty->noty('message', 'alert', 'centerRight');

Yii::app()->noty->noty('message', 'alert', 'bottomLeft');
Yii::app()->noty->noty('message', 'alert', 'bottomCenter');
Yii::app()->noty->noty('message', 'alert', 'bottomRight');
Yii::app()->noty->noty('message', 'error', 'bottom');
?>
<h3>Notify</h3>
<button id="show">Show</button>
<script type="text/javascript">
    $(function() {

        var n = noty({
            text: 'message',
            type: 'success',
            timeout: 3000,
            dismissQueue: true,
            layout: 'center',
            theme: 'defaultTheme'
        });

        $('#show').click(function() {
           generate('warning');
        });

        function generate(type) {
            var n = noty({
                text: type,
                type: type,
                dismissQueue: true,
                layout: 'center',
                theme: 'defaultTheme'
            });
            return n;
        }

    });
</script>