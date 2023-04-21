window.last_result = '';

// event hook
window.printMousePos = function(event) {

    // console.log("clientX: " + event.clientX + " - clientY: " + event.clientY); 

    // get clicked element
    elementClicked = document.elementFromPoint(event.clientX, event.clientY);
    
    // return xpath
    // console.log(createXPathFromElement(elementClicked));
    window.last_result = createXPathFromElement(elementClicked);
}


window.getLastResult = function () {
    return window.last_result;
}


// create xpath
window.createXPathFromElement = function(elm) { 
    var allNodes = document.getElementsByTagName('*'); 
    for (var segs = []; elm && elm.nodeType == 1; elm = elm.parentNode) 
    { 
        if (elm.hasAttribute('id') && false) { 
                var uniqueIdCount = 0; 
                for (var n=0;n < allNodes.length;n++) { 
                    if (allNodes[n].hasAttribute('id') && allNodes[n].id == elm.id) uniqueIdCount++; 
                    if (uniqueIdCount > 1) break; 
                }; 
                if ( uniqueIdCount == 1) { 
                    segs.unshift('id("' + elm.getAttribute('id') + '")'); 
                    return segs.join('/'); 
                } else { 
                    segs.unshift(elm.localName.toLowerCase() + '[@id="' + elm.getAttribute('id') + '"]'); 
                } 
        } else if (elm.hasAttribute('class') && false) { 
            segs.unshift(elm.localName.toLowerCase() + '[@class="' + elm.getAttribute('class') + '"]'); 
        } else { 
            for (i = 1, sib = elm.previousSibling; sib; sib = sib.previousSibling) { 
                if (sib.localName == elm.localName)  i++; }; 
                segs.unshift(elm.localName.toLowerCase() + '[' + i + ']'); 
        }; 
    }; 

    return segs.length ? '/' + segs.join('/') : null; 
}; 


// Add click listener
document.addEventListener("click", window.printMousePos);