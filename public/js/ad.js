$("#add_image").click(function(){

    // je récupère le nombre d'image ajouté
    let index = +$("#widgets_counter").val();

    console.log(index);
    // je récupère le prototype du formulaire des images
    let templateImage = $("#annonce_images").data("prototype").replace(/__name__/g, index); 

    // j'intecte le prototype dans le fsomulaire
    $("#annonce_images").append(templateImage);

    $("#widgets_counter").val(index+1)

    handleDeleteButton();

});

function handleDeleteButton()
{
    $("button[data-action='delete']").click(function(){

        const target = this.dataset.target;
        console.log(target);
        $(target).remove();

    });
}

function updateCounter()
{
    const count = +$("#annonce_images div.form-group").length;
    $("#widgets_counter").val(count);
}

handleDeleteButton();
updateCounter();


// fonction qui affiche la modal pour supprimer uen annonce

$("#modalAdDelete").on("show.bs.modal", function(e){
    const href = $("#confirmDelete").data("href");
    const title = $("#confirmDelete").data("name");

    $("#adName").html(title);
    $("#btn-delete").attr("href",href);
})

function test(){
    
}