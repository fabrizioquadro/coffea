(function() {
    ///Extra settings for adding values to quadrants
    ///enable by adding to settings.js > W.customerScripts = { 0: "../software/html5/autojump.js" }; //example
    ///
    var autoiconjump = true;
    var autojumpmethod = 2;
    
    var autoicondiagonaladdqw1 = 0;  //quadrant 1 addition diagonal position offset
    var autoicondiagonaladdqw2 = 0;  //50; //quadrant 2 addition diagonal position offset
    var autoicondiagonaladdqw3 = 0;  //50; //quadrant 3 addition diagonal position offset
    var autoicondiagonaladdqw4 = 0;  //50; //quadrant 4 addition diagonal position offset
    var prevarea = 1;
    var AutoJumpMove, AutoJump;

    if(autojumpmethod == 1) {
      AutoJump = function() {
        if(autoiconjump) {           
           var runMe = function() { 
                    var lposx = W.lposx, lposy = W.lposy;
                    var ML = W.ML;
                    var mousewidth = ML.V.width; // + ML.V.width2;
                    var mouseheight = ML.V.height; // + ML.V.height2;
                    if(lposy < W.maxh2 && lposx < W.maxw2) { 
                       //when user enters left top area
                       if(prevarea !== 1) { 
                           prevarea = 1; 
                           if(W.setPosMouse() && autoicondiagonaladdqw1 > 0) {
                             ML.V.left += autoicondiagonaladdqw1;
                             ML.V.top += autoicondiagonaladdqw1;
                             W.adjustIconPosition();
                           }
                       }
                    } else if(lposy < W.maxh2 && lposx > W.maxw2) { 
                       //when user enters right top area
                       if(prevarea !== 2) { 
                           prevarea = 2; 
                           if(W.setPosMouse()) {
                             ML.V.left -= (mousewidth + autoicondiagonaladdqw2);
                             ML.V.top += autoicondiagonaladdqw2;
                             W.adjustIconPosition();
                           }
                        
                       }
                    } else if(lposy > W.maxh2 && lposx < W.maxw2) { 
                       //when user enters bottom left area
                       if(prevarea !== 3) { 
                           prevarea = 3;
                           if(W.setPosMouse()) {
                             ML.V.left += autoicondiagonaladdqw3;
                             ML.V.top -= (mouseheight + autoicondiagonaladdqw3);
                             W.adjustIconPosition();
                           }
                       }
                    } else if(lposy > W.maxh2 && lposx > W.maxw2) { 
                       //when user enters botton right area
                       if(prevarea !== 4) { 
                           prevarea = 4;
                           if(W.setPosMouse()) {
                             ML.V.left -= (mousewidth + autoicondiagonaladdqw4);
                             ML.V.top -= (mouseheight + autoicondiagonaladdqw4);
                             W.adjustIconPosition();
                           }
                       }
                    }    
           };
           AutoJump = function() { window.setTimeout(runMe, 0); }
           AutoJump();
        } else { AutoJump = AutoJumpMove = function() { }; } 
      };
    
      AutoJumpMove = AutoJump;
    
    } else if(autojumpmethod == 2) {
      ///here may come different implementation
    
      var AutoJumpM2 = function() {
                    var lposx = W.lposx, lposy = W.lposy;
                    var ML = W.ML;
                    var mousewidth = ML.V.width; // + ML.V.width2;
                    var mouseheight = ML.V.height; // + ML.V.height2;
                    var mousewidthcur = Math.round((ML.V.width + 22) * 1.2);
                    var mouseheightcur = Math.round((ML.V.height + 22) * 1.2);
    
                    var area2x = W.maxw - mousewidthcur;
                    var area2y = W.maxh - mouseheightcur;
    
                    var area3y = area2y;
                    var area3x = Math.round(area2x - (area2x/10));
    
                    if(lposx > area2x) { 
                      if(lposy < area2y) {
                           prevarea = 2;
                           if(W.setPosMouse()) {
                             ML.V.left -= (mousewidth + autoicondiagonaladdqw2);
                             ML.V.top += autoicondiagonaladdqw2;
                             W.adjustIconPosition();
                           }
                      } else { 
                           prevarea = 4;
                           if(W.setPosMouse()) {
                             ML.V.left -= (mousewidth + autoicondiagonaladdqw4);
                             ML.V.top -= (mouseheight + autoicondiagonaladdqw4);
                             W.adjustIconPosition();
                           }         
                      }
                    } else if(lposy > area3y) {
                      if(lposx < area3x) {
                           prevarea = 3;
                           if(W.setPosMouse()) {
                             ML.V.left += autoicondiagonaladdqw3;
                             ML.V.top -= (mouseheight + autoicondiagonaladdqw3);
                             W.adjustIconPosition();
                           }
                      } else { 
                           prevarea = 4;
                           if(W.setPosMouse()) {
                             ML.V.left -= (mousewidth + autoicondiagonaladdqw4);
                             ML.V.top -= (mouseheight + autoicondiagonaladdqw4);
                             W.adjustIconPosition();
                           }
                      }
                    } else if(prevarea != 1) { 
                       prevarea = 1;
                       if(W.setPosMouse() && autoicondiagonaladdqw1 > 0) {
                         ML.V.left += autoicondiagonaladdqw1;
                         ML.V.top += autoicondiagonaladdqw1;
                         W.adjustIconPosition();
                       }
                    } else { return false; }
                    return true;
      };
    
      AutoJump = function() { 
        if(autoiconjump) {
          AutoJump = function() { window.setTimeout(AutoJumpM2, 0); };
          AutoJump();
        } else { AutoJump = AutoJumpMove = function() { }; } 
      };
    
      AutoJumpMove = function() { 
        if(autoiconjump) {
          var runMe = function() { if(!AutoJumpM2()) { W.setPosMouse(); } };
          AutoJumpMove = function() { window.setTimeout(runMe, 0); };
          AutoJumpMove();
        } else { AutoJumpMove = AutoJump = function() { }; } 
      };
    }
    
    W.jumpAfterIconMove = AutoJumpMove;
    W.jumpAfterScreenClick = AutoJump;
})();