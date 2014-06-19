  function isNumberKey(evt)
       {
		
          var c = (evt.which) ? evt.which : event.keyCode;
		 
		 
         if(c >= 48 && c <= 57) //13 = enter
             {return true;}
			else if(c==13){
			return true;}
			
          return false;
       }
  function ReadableNumber(nStr)
 {
		  nStr += '';
		  x = nStr.split('.');
		  x1 = x[0];
		  x2 = x.length > 1 ? '.' + x[1] : '';
		  var rgx = /(\d+)(\d{3})/;
		  while (rgx.test(x1)) {
		   x1 = x1.replace(rgx, '$1' + ',' + '$2');
		  }
		  return x1 + x2;
	}