 function imageSelectHandler(input) {
                $(input.parentNode.parentNode.parentNode).find('#'+input.id+'_box').remove();
                $(input.parentNode.parentNode.parentNode).append($('<div>').prop({id:input.id+'_box'}).css('padding-top','10px'))
                var box=$('#'+input.id+'_box'),file_id=input.id;
                var oFile = $('#'+file_id)[0].files[0];
                var rFilter = /^(image\/jpeg|image\/png|image\/gif)$/i;
                var iwidth = $('#'+file_id).attr('data-width'),
                    iheight = $('#'+file_id).attr('data-height');
                var pRatio_h = iheight / iwidth;
                if (! rFilter.test(oFile.type)) {
                    alert('Please select a valid image file (jpg png and gif are allowed)');
                    $('#'+file_id).prop('value','');
                    return false;
                }
                // // check for file size
                // if (oFile.size > 250 * 1024) {
                //     alert('You have selected too big file, please select a one smaller image file');
                //     return;
                // }
                box.find('img.jcrop-preview').remove();
                box.find('div#preview-pane').remove();
                box.find('input[type=hidden]').remove();
                var oImage = $('<img>').prop({id:file_id+'_show'});
                oImage.css({'max-width':'500px'});
                oImage.hide();
                oImage.appendTo(box);
                var pImage = $('<img>');
                pImage.addClass('jcrop-preview');
                pImage.hide();
                box.append('<input type="hidden" id="'+file_id+'_x1" name="'+file_id+'_x1" value="0" />');
                box.append('<input type="hidden" id="'+file_id+'_y1" name="'+file_id+'_y1" value="0" />');
                box.append('<input type="hidden" id="'+file_id+'_w" name="'+file_id+'_w" />');
                box.append('<input type="hidden" id="'+file_id+'_h" name="'+file_id+'_h" />');
                box.append('<input type="hidden" id="'+file_id+'_iWidth" name="'+file_id+'_iWidth" value="'+iwidth+'" />');
                box.append('<input type="hidden" id="'+file_id+'_iHeight" name="'+file_id+'_iHeight" value="'+iheight+'" />');
                box.append($('<div>').addClass('col-md-6 responsive-1024').prop({id:'preview-pane'}).css({'width':'264px','height':((pRatio_h*250)+14)+'px'}).append($('<div>').addClass('preview-container').css({'width':'250px','height':(pRatio_h*250)+'px'}).append(pImage)));
                var oReader = new FileReader();
                oReader.onload = function(e) {

                     // e.target.result contains the DataURL which we can use as a source of the image
                    
                    
                    
                    var jcrop_api,
                    boundx,
                    boundy,
                    // Grab some information about the preview pane
                    $preview = box.find('div#preview-pane'),
                    $pcnt = box.find('div.preview-container'),
                    $pimg = box.find('img[class=jcrop-preview]'),
                    aRatio = iwidth/iheight,
                    xsize = $pcnt.width(),
                    ysize = $pcnt.height();
                    var oImage=box.find('#'+file_id+'_show');
                    var oImage_dom=document.getElementById(file_id+'_show');
                    oImage.prop({src:e.target.result});
                    $pimg.prop({src:e.target.result});
                    oImage.Jcrop({
                    bgColor: null,
                    onChange: updatePreview,
                    onSelect: updatePreview,
                    onSelect: updateCoords,
                    aspectRatio: aRatio
                  },function(){
                  // Use the API to get the real image size
                  var bounds = this.getBounds();
                  boundx = bounds[0];
                  boundy = bounds[1];
                  // Store the API in the jcrop_api variable
                  jcrop_api = this;
                  
                  // Move the preview into the jcrop container for css positioning
                  $preview.appendTo(jcrop_api.ui.holder);
                });
                   
                 function updateCoords(c)
                  {
                    if(oImage_dom.naturalWidth>=box.find('div.jcrop-holder img').width())
                        var oRatio_w=oImage_dom.naturalWidth/box.find('div.jcrop-holder img').width();
                    if(oImage_dom.naturalHeight>=box.find('div.jcrop-holder img').height())
                        var oRatio_h=oImage_dom.naturalHeight/box.find('div.jcrop-holder img').height();
                    if(oImage_dom.naturalWidth<box.find('div.jcrop-holder img').width())
                        var oRatio_w=1;
                    if(oImage_dom.naturalHeight<box.find('div.jcrop-holder').find('img').height())
                        var oRatio_h=1;
                    $('#'+file_id+'_x1').val(c.x*oRatio_w);
                    $('#'+file_id+'_y1').val(c.y*oRatio_h);
                    $('#'+file_id+'_w').val(c.w*oRatio_w);
                    $('#'+file_id+'_h').val(c.h*oRatio_h);
                  };

                function updatePreview(c)
                {
                  if (parseInt(c.w) > 0)
                  {
                    var rx = xsize / c.w;
                    var ry = ysize / c.h;

                    $pimg.css({
                      width: Math.round(rx * boundx) + 'px',
                      height: Math.round(ry * boundy) + 'px',
                      marginLeft: '-' + Math.round(rx * c.x) + 'px',
                      marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                  }
                };
                console.log(iwidth/iheight);
                if(aRatio>=0){
                    $('#'+file_id+'_w').val($pimg.width());
                    $('#'+file_id+'_h').val($pimg.width()*(iheight/iwidth));
                    $pimg.css({
		                width: 250 + 'px',
		                height: 250*(box.find('img').height()/box.find('img').width()) + 'px',
		                marginLeft: '0px',
		                marginTop: '0px'
                	});
                    
                }else{
                    $('#'+file_id+'_w').val($pimg.height()*(iwidth/iheight));
                    $('#'+file_id+'_h').val($pimg.height());
                    $pimg.css({
		                width: 250*(box.find('img').width()/box.find('img').height()) + 'px',
		                height: 250*(box.find('img').height()/box.find('img').width()) + 'px',
		                marginLeft: '0px',
		                marginTop: '0px'
                	});
                }
				



                // $('#'+oImage.prop('id')).show();
                $pimg.show();
                    
                    
                }
                

               
                oReader.readAsDataURL(oFile);

            }