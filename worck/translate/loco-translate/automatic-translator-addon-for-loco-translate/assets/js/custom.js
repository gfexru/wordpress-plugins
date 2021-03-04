/*
    It is master branch code
*/

/*
   it is testing branch
*/
!function( window, $ ){
  createSettingsPopup();
  createStringsPopup();
  //create_deepl_popup();
  //createSettingsPopup();

/*
   String Translate Model
 */
// Get the modal
var gModal = document.getElementById("atlt_strings_model");

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == gModal) {
      gModal.style.display = "none";
  }
} 

// Get the <span> element that closes the modal
$("#atlt_strings_model").find(".close").on("click",function() {
  $("#atlt_strings_model").fadeOut("slow");
});

  // integrates auto traslator button in editor
 function newaddAutoTranslationBtn(){
        if($("#loco-toolbar").find("#cool-auto-translate-btn").length>0){
            $("#loco-toolbar").find("#cool-auto-translate-btn").remove();
        }
        const locoActions= $("#loco-toolbar").find("#loco-actions");
        const proActiveBtn='<fieldset><button id="cool-auto-translate-btn" class="button has-icon icon-translate">Auto Translate</button></fieldset>';
       
        locoActions.append(proActiveBtn);
     
  }

// create auto translate settings popup
function createSettingsPopup(){
      let preloaderImg=extradata['preloader_path'];
      let ytPreviewImg=extradata['yt_preview'];
      let gtPreviewImg=extradata['gt_preview'];
      let dplPreviewImg=extradata['dpl_preview'];
      let modelHTML=` 
      <!-- The Modal -->
      <div id="atlt-dialog" title="Auto Translate (No API Required)" style="display:none;">
      <div class="atlt-settings">
      
      <strong class="atlt-heading" style="margin-bottom:10px;display:inline-block;">Translate Using Yandex Page Translate Widget</strong>
      <div class="inputGroup">
      <button id="atlt_yandex_transate_btn" class="notranslate button button-primary">Yandex Translate</button>
      <span class="proonly-button alsofree">✔ Available</span>
      <br/><a href="https://translate.yandex.com/" target="_blank"><img style="margin-top: 5px;" src="${ytPreviewImg}" alt="powered by Yandex Translate Widget"></a>
      </div>
      <hr/>

      <strong class="atlt-heading" style="margin-bottom:10px;display:inline-block;">Translate Using Google Page Translate Widget</strong>
      <div class="inputGroup">
      <button id="atlt_gtranslate_btn" disabled="disabled" class="notranslate button button-primary">Google Translate</button>
      <span class="proonly-button"><a href="https://bit.ly/locoaddon" target="_blank" title="Buy Pro">PRO Only</a></span>
      <br/><a href="https://translate.google.com/" target="_blank"><img style="margin-top: 5px;" src="${gtPreviewImg}" alt="powered by Google Translate Widget"></a>
      </div>
      <hr/>
      
      <strong class="atlt-heading" style="margin-bottom:10px;display:inline-block;">Translate Using Deepl Doc Translator</strong>
      <div class="inputGroup">
      <button  disabled="disabled" id="atlt_deepl_btn" class="notranslate button button-primary">DeepL Translate</button>
      <span class="proonly-button"><a href="https://bit.ly/locoaddon" target="_blank" title="Buy Pro">PRO Only</a></span>
      <br/><a href="https://www.deepl.com/en/translator" target="_blank"><img style="margin-top: 5px;" src="${dplPreviewImg}" alt="powered by DeepL Translate"></a>
      <br/>DeepL translation is beeter than Google, Microsoft & other auto-translation providers - <a href="https://techcrunch.com/2017/08/29/deepl-schools-other-online-translators-with-clever-machine-learning/" target="_blank">read review by techcruch</a>
      </div>
      <hr/>

      <ul style="margin: 0;">
      <li><span style="color:green">✔</span> Unlimited Translations</li>
      <li><span style="color:green">✔</span> No API Key Required</li>
      <li><span style="color:green">✔</span> Check Languages Support - <a href="https://yandex.com/support/translate/supported-langs.html" target="_blank">Yandex</a>, <a href="https://en.wikipedia.org/wiki/Google_Translate#Supported_languages" target="_blank">Google</a>, <a href="https://www.deepl.com/en/translator" target="_blank">DeepL</a></li>
      </ul>

      </div>
      </div>
    `;
    $("body").append( modelHTML );
  }

  /**
   * generate model popup HTML
   */  
  function createStringsPopup(){
    let modelHTML=` 
    <!-- The Modal -->
    <div id="atlt_strings_model" class="modal atlt_custom_model">
        <!-- Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <span class="close ">&times;</span>
            <h2 class="notranslate">Automatic Translations </h2>
            <div class="save_btn_cont">
            <button class="notranslate save_it button button-primary" disabled="true">Merge Translation</button>
            </div>

            <div style="display:none" class="ytstats">
              Wahooo! You have saved your valauble time via auto translating 
               <strong class="totalChars"> </strong> characters  using 
                <strong> 
                <a href="https://wordpress.org/support/plugin/automatic-translator-addon-for-loco-translate/reviews/#new-post" target="_new">
                Loco Automatic Translate Addon</a>
              </strong>     
            </div>

          </div>
            <div class="notice inline notice-info is-dismissible">Plugin will not translate any strings with HTML or special characters because Yandex Translator currently
             does not support HTML and special characters translations. 
            You can edit translated strings inside Loco Translate Editor after merging the translations. Only special chracters (%s, %d) fixed at the time of merging of the translations.</div>
            <div class="notice inline notice-info is-dismissible">Machine translations are not 100% correct.
             Please verify strings before using on production website.</div>
          <div class="modal-body">
            <div class="my_translate_progress">Automatic translation is in progress....<br/>It will take few minutes, enjoy ☕ coffee in this time!</div>
            <h3>Choose language</h3>
            <div id="ytWidget">..Loading</div>
            <br/>
            <div class="string_container">               
                <table class="scrolldown" id="stringTemplate">
                    <thead>
                    <th class="notranslate">S.No</th>
                    <th class="notranslate">Source Text</th>
                    <th class="notranslate">Translation</th>
                    </thead>
                    <tbody id="yandex_string_tbl">
                    </tbody>
                </table>
            </div>
          </div>
      <div class="modal-footer">
            <div class="save_btn_cont">
            <button class="notranslate save_it button button-primary" disabled="true">Merge Translation</button>
            </div>
            <div style="display:none" class="ytstats">
            Wahooo! You have saved your valauble time via auto translating 
               <strong class="totalChars"></strong> characters  using 
                <strong> 
                <a href="https://wordpress.org/support/plugin/automatic-translator-addon-for-loco-translate/reviews/#new-post" target="_new">
                Loco Automatic Translate Addon</a>
              </strong>     
            </div>
      </div>
        </div>
      </div>`;
      
    $("body").append( modelHTML );
}




  
}( window, jQuery );