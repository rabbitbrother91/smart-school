CKEDITOR.editorConfig = function(config){
    
config.allowedContent = true;
config.extraAllowedContent = 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}';
CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty.i = 0;
config.protectedSource.push(/<i class="fa[s|r|l|b] [A-Za-z0-9\-]+"><\/i>/g);
CKEDITOR.dtd.$removeEmpty['span'] = false;
config.contentsCss = baseurl+'/backend/plugins/ckeditor/plugins/font-awesome/css/font-awesome.css';


     // config.uiColor = '#AADC6E';
    config.toolbar = 'Admin_Exam';
    config.toolbar_Admin_Exam =[
    {name: 'wirisplugins', items: ['ckeditor_wiris_formulaEditor', 'ckeditor_wiris_formulaEditorChemistry']},
    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline' ] },
    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    config.height = 200,
    
    config.wordcount = {

    // Whether or not you Show Remaining Count (if Maximum Word/Char/Paragraphs Count is set)
    showRemaining: false,
    
    // Whether or not you want to show the Paragraphs Count
    showParagraphs: false,

    // Whether or not you want to show the Word Count
    showWordCount: true,

    // Whether or not you want to show the Char Count
    showCharCount: false,
    
    // Whether or not you want to Count Bytes as Characters (needed for Multibyte languages such as Korean and Chinese)
    countBytesAsChars: false,

    // Whether or not you want to count Spaces as Chars
    countSpacesAsChars: false,

    // Whether or not to include Html chars in the Char Count
    countHTML: false,
    
    // Whether or not to include Line Breaks in the Char Count
    countLineBreaks: false,
    
    // Whether or not to prevent entering new Content when limit is reached.
    hardLimit: true,
    
    // Whether or not to to Warn only When limit is reached. Otherwise content above the limit will be deleted on paste or entering
    warnOnLimitOnly: false,

    // Maximum allowed Word Count, -1 is default for unlimited
    maxWordCount: -1,

    // Maximum allowed Char Count, -1 is default for unlimited
    maxCharCount: -1,
    
    // Maximum allowed Paragraphs Count, -1 is default for unlimited
    maxParagraphs: -1,

    // How long to show the 'paste' warning, 0 is default for not auto-closing the notification
    pasteWarningDuration: 0,

    // Add filter to add or remove element before counting (see CKEDITOR.htmlParser.filter), Default value : null (no filter)
    filter: new CKEDITOR.htmlParser.filter({
        elements: {
            div: function( element ) {
                if(element.attributes.class == 'mediaembed') {
                    return false;
                }
            }
        }
    }),

}
    ];


};