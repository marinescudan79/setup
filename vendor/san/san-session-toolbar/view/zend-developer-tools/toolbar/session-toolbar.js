/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
function sansessionToolbar_postDataWithAjax(url, setViewCallBack, params)
{
    var xmlhttp; // @see http://www.w3schools.com/ajax/ajax_xmlhttprequest_create.asp
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.open("POST", url, true);

    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Accept", "application/json");

    if (params != undefined) {
        xmlhttp.setRequestHeader("Content-length", params.length);
    }

    xmlhttp.setRequestHeader("Connection", "close");
    if (params != undefined) {
        xmlhttp.send(params);
    } else {
        xmlhttp.send(null);
    }

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            setViewCallBack(JSON.parse(xmlhttp.responseText))
        }
    }
}

function sanSessionToolbar_removeSessionByKey(containerName, keysession)
{
    var params = "containerName="+containerName+"&keysession="+keysession;
    sansessionToolbar_postDataWithAjax(san_session_toolbar_base_url+'/san-session-toolbar/removesession', function(html) {
        if (html.success) {
            var elements = document.getElementsByClassName("san-session-toolbar-info-containerName-"+containerName+"-keysession-"+keysession);
            while (elements.length > 0) {
                elements[0].parentNode.removeChild(elements[0]);
            }
        } else {
            alert('No session registered with container named "'+containerName+'" and key session "'+keysession+'" or session already removed');
        }
    }, params);
}

function sanSessionToolbar_reloadSession()
{
    sansessionToolbar_postDataWithAjax(san_session_toolbar_base_url+'/san-session-toolbar/reloadsession', function(html) {
        document.getElementById('san-session-toolbar-detail').innerHTML = html.san_sessiontoolbar_data_renderedContent;
    });
}

function sanSessionToolbar_clearAllSession()
{
    sansessionToolbar_postDataWithAjax(san_session_toolbar_base_url+'/san-session-toolbar/clearsession', function(html) {
        document.getElementById('san-session-toolbar-detail').innerHTML = html.san_sessiontoolbar_data_renderedContent;
    });
}

function sanSessionToolbar_clearSessionOfContainer(byContainer)
{
    var params = "byContainer="+byContainer;
    sansessionToolbar_postDataWithAjax(san_session_toolbar_base_url+'/san-session-toolbar/clearsession', function(html) {
        document.getElementById('san-session-toolbar-detail').innerHTML = html.san_sessiontoolbar_data_renderedContent;
    }, params);
}

function editSessionByKey(containerName, keysession)
{
    document.getElementById("san-session-toolbar-info-containerName-"+containerName+"-keysession-"+keysession).style.display = 'none';
    document.getElementById("san-edit-mode-session-toolbar-info-containerName-"+containerName+"-keysession-"+keysession).style.display = 'block';
}

function sanSessionToolbar_cancelSaveSessionByKey(containerName, keysession)
{
    document.getElementById("san-session-toolbar-info-containerName-"+containerName+"-keysession-"+keysession).style.display = 'block';
    document.getElementById("san-edit-mode-session-toolbar-info-containerName-"+containerName+"-keysession-"+keysession).style.display = 'none';

    // empty error
    $('.errorMessage-containerName-'+containerName+'-keysession-'+keysession).html('');
}

function sanSessionToolbar_saveSessionByKey(containerName, keysession)
{
    var params = "containerName="+containerName+"&keysession="+keysession+"&sessionvalue="+document.getElementById('san-detail-value-containerName-'+containerName+'-keysession-'+keysession).value;
    sansessionToolbar_postDataWithAjax(san_session_toolbar_base_url+'/san-session-toolbar/savesession', function(html) {
        if (html.success) {
            document.getElementById('san-session-toolbar-detail').innerHTML = html.san_sessiontoolbar_data_renderedContent;
        } else {
            if (JSON.stringify(html.errorMessages) === '[]') {
                alert('Save session failed, check if no session registered with container named "'+containerName+'" and key session "'+keysession+'" or session already removed');
            } else {
                $('.errorMessage-containerName-'+containerName+'-keysession-'+keysession).html(html.errorMessages[keysession][0]['isEmpty']);
            }
        }
    }, params);
}
