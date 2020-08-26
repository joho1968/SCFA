(function( $ ) {
	'use strict';

	/**
     * scfa-admin.js
     *
     * This file is part of SCFA. SCFA is free software.
     *
     * You may redistribute it and/or modify it under the terms of the
     * GNU General Public License version 2, as published by the Free Software
     * Foundation.
     *
     * SCFA is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with the SCFA package. If not, write to:
     *  The Free Software Foundation, Inc.,
     *  51 Franklin Street, Fifth Floor
     *  Boston, MA  02110-1301, USA.
	 */

     /* Small extension to insert text at caret position in textarea
      * https://stackoverflow.com/questions/15975922/how-to-use-jquery-insertatcaret-function
      */
    jQuery.fn.extend({
        insertAtCaret: function(myValue){
            return this.each(function(i) {
                if (document.selection) {
                    //For browsers like Internet Explorer
                    this.focus();
                    sel = document.selection.createRange();
                    sel.text = myValue;
                    this.focus();
                } else if (this.selectionStart || this.selectionStart == '0') {
                    //For browsers like Firefox and Webkit based
                    var startPos = this.selectionStart;
                    var endPos = this.selectionEnd;
                    var scrollTop = this.scrollTop;
                    this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                    this.focus();
                    this.selectionStart = startPos + myValue.length;
                    this.selectionEnd = startPos + myValue.length;
                    this.scrollTop = scrollTop;
                } else {
                    this.value += myValue;
                    this.focus();
                }
            });
        }
    });

    /* Add click handler for our button */

    $(function() {
        $('body').on('click','#scfa_shortcode', handle_scfa_button);

    });

    /*
     * We may have to handle inserting text in the 'content' selector, which
     * is WordPress' textarea element for plain text editing.
     */
    function handle_scfa_button(e) {
        if ($('#content').is(':visible')) {
            $('#content').insertAtCaret ('[scfa icon="" size="" class="" css=""][/scfa]');
        } else if (typeof (tinymce) != null) {
            if (typeof (tinymce.activeEditor) != null) {
                tinymce.activeEditor.execCommand( 'mceInsertContent', false,
                                                  '[scfa icon="" size="" class="" css=""][/scfa]');
            }
        }//tinyMCE
    }

})( jQuery );
