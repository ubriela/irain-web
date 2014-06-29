$(document).ready(function(){
     $(document).on('click','#btndel',function(){
                var elements = deleteArray.join(',');
                if(deleteArray.length!=0){
                    $.ajax({
                        type: 'POST',
                        url: baseurl+'index.php/requester/delete_tasks',
                        data: 'taskids='+elements,
                        success:function(data){
                            if(data.status=='success'){
                                loadTask(numbertask);
                                numbertask = 2;
                                offset = 0;
                            }
                        }
                    });
                }
            });
})