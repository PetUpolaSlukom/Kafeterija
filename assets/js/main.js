window.onload = () => {
    
    $(document).on("click",".navbar-toggler",function(){
        $("#responsive-meni")
            .css({
                "left" : "0",
                "top" : `${$(".navbar").height()}px`
            })
            .toggle("slow");
    });

}