<div class="row">
    <div class="col-xs-12 col-sm-{{ labelRate }} col-md-{{ labelRate }} col-lg-{{ labelRate }}">
        <div class="form-group formLabel">
            <label for="{{ requestKey }}">
                <b>{{ label }}</b>
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-{{ inputRate }} col-md-{{ inputRate }} col-lg-{{ inputRate }}">
        <div class="form-group">
            <div class="custom-file mt-3 mb-3">
                <input type="file" class="custom-file-input" id="{{ requestKey }}" name="{{ requestKey }}">
                <label class="custom-file-label" for="customFile">{{ label }}</label>
            </div>
            <div class="validationMessage error" id="{{ requestKey }}-validationMessage" style="padding-top:4px;">{{ validationMessage }}</div>
        </div>
    </div>
</div>
<script>
// new nicEditor({fullPanel : true}).panelInstance('{{ requestKey }}', {hasPanel : true});
</script>