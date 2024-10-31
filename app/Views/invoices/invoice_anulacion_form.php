<?php echo form_open(get_uri("invoices/anulacion"), array("id" => "invoice-anulacion", "class" => "general-form", "role" => "form")); ?>
<div id="send_invoice-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="id" value="<?php echo $invoice_info->id; ?>" />

            <div class="form-group">
                <div class="row">
                    <label for="external_id" class=" col-md-3">External ID</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "external_id",
                            "name" => "external_id",
                            "value" => $invoice_info->external_id,
                            "class" => "form-control",
                            "placeholder" => "",
                            "readonly" => "true",
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="fecha_envio" class=" col-md-3">Fecha de Documento</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "fecha_envio",
                            "name" => "fecha_envio",
                            "value" => $invoice_info->bill_date,
                            "class" => "form-control",
                            "placeholder" => "",
                            "readonly" => "true",
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <label for="motivo" class=" col-md-3">Motivo</label>
                    <div class="col-md-9">
                        <?php
                        echo form_input(array(
                            "id" => "motivo",
                            "name" => "motivo",
                            "value" => '',
                            "class" => "form-control",
                            "placeholder" => '',
                            "data-rule-required" => true,
                            "data-msg-required" => app_lang("field_required"),
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="send" class="icon-16"></span> <?php echo app_lang('send'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#motivo").focus();
    });

    $("#invoice-anulacion").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    appAlert.success(result.message, {duration: 10000});
                    if (typeof updateInvoiceStatusBar == 'function') {
                        updateInvoiceStatusBar(<?php echo $invoice_info->id; ?>);
                    }

                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    appAlert.error(result.message);
                }
            }
        });

</script>