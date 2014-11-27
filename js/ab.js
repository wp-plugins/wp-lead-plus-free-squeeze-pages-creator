/**
 * Created by gatovago on 11/5/14.
 */


jQuery(document).ready(function(){
    localStorage.setItem("vgt_ab_id", 0);
    //this variable will be reset each time:
        //1. Edit test clicked
        //2. Create new test clicked
        //3. Change test selection in edit mode

    //GET ALL POPUPS/WIDGETS/PAGE AND APPEND TO THE APPROPRIATE LIST TO SELECT LATER
    //get all available popups and put into the option selection to start editing
    var data = {
        action: "vgt_get_popup_widget_options",
        type: "popup"
    };

    jQuery.post(ajaxurl, data, function(response){
        var options_array = vgt_parse_json_output(response);


        var options = "";
        for (var i = 0; i < options_array.length; i ++)
        {
            options += '<span><input class="form-control" type="checkbox" alt_id="'+options_array[i].id+'" name="popup_options" value='+options_array[i].id+' />  '+vgt_de_serialize_data(options_array[i].title)+'</span>';
        }

        jQuery("#popup_selector").html("");
        jQuery("#popup_selector").append(options);
    });

    //get all available popups and put into the option selection to start editing
    var data = {
        action: "vgt_get_popup_widget_options",
        type: "widget"
    };

    jQuery.post(ajaxurl, data, function(response){
        var options_array = vgt_parse_json_output(response);


        var options = "";
        for (var i = 0; i < options_array.length; i ++)
        {
            options += '<span><input class="form-control" type="checkbox" alt_id="'+options_array[i].id+'" name="widget_options" value='+options_array[i].id+' />  '+vgt_de_serialize_data(options_array[i].title)+'</span>';
        }

        jQuery("#widget_selector").html("");
        jQuery("#widget_selector").append(options);



    });

    var data = {
        action: "vgt_get_all_squeeze"
    };

    jQuery.post(ajaxurl, data, function(response){
        var pages = vgt_parse_json_output(response);

        var page_list = "";
        for (var i = 0; i < pages.length; i ++)
        {
            page_list += '<span><input class="form-control" type="checkbox" alt_id="'+pages[i].id+'" name="popup_options" value='+pages[i].id+' />  '+(pages[i].title)+'</span>';
        }

        jQuery("#squeeze_selector").html("");
        jQuery("#squeeze_selector").append(page_list);
    });

    //SHOW FIELDS TO CREATE TESTS
    jQuery(document).on("click", "#ab_create_test", function(){
        jQuery("#ab_edit_test").removeClass("vgt-header-active");
        jQuery(this).addClass("vgt-header-active");

        jQuery(".ab_page_type").parent().hide();
        jQuery("#on_category_value").parent().hide();
        jQuery("#ab_squeeze_url").parent().hide();

        jQuery("#vgt_ab_left input[type=text], #vgt_ab_left select, #vgt_ab_left textarea").val("");

        jQuery("#ab_created_test_list").parent().hide();

        localStorage.setItem("vgt_ab_id", 0);
        localStorage.setItem(AB_SQUEEZE_ID, 0);


        jQuery("#ab_create").fadeIn();
    });


    //SHOW FIELDS TO EDIT TESTS
    jQuery(document).on("click", "#ab_edit_test", function(){
        jQuery("#ab_create_test").removeClass("vgt-header-active");
        jQuery(this).addClass("vgt-header-active");

        jQuery(".ab_page_type").parent().hide();
        jQuery("#on_category_value").parent().hide();
        jQuery("#ab_squeeze_url").parent().hide();


        jQuery("#vgt_ab_left input[type=text], #vgt_ab_left select, #vgt_ab_left textarea").val("");

        localStorage.setItem(AB_SQUEEZE_ID, 0);

        //get all a/b tests
        var data = {
            action: "vgt_get_all_ab_tests"
        }

        jQuery.post(ajaxurl, data, function(response){
            var data = vgt_parse_json_output(response);
            console.log(data);
            var string = "<option value=''></option>";
            for (var i = 0; i < data.length; i++)
            {
                string += '<option page_type="'+data[i].page_type+'" value='+data[i].id+' >'+ vgt_de_serialize_data(data[i].test_name) +'</option>';
            }

            jQuery("#ab_created_test_list").html(string);

            jQuery("#ab_created_test_list").parent().show();
            jQuery("#ab_create").fadeIn();

        });
    });

    //EDIT THE TEST
    //On change the created test
    jQuery(document).on("change", "#ab_created_test_list", function(){
        /*
        1. Get ID of the test
        2. Get the details of the test
        3. Put the values to the input fields
         */
        var data = {
            action: "vgt_get_ab_details",
            ab_id : jQuery(this).val()
        }

        localStorage.setItem("vgt_ab_id", jQuery(this).val());

        jQuery.post(ajaxurl, data, function(response){
            var data = vgt_parse_json_output(response);


            var checked_options = JSON.parse(vgt_de_serialize_data(data.selected));
            //Propagate data to appropriate fields
            jQuery("#ab_test_name").val(vgt_de_serialize_data(data.test_name));

            jQuery("#select_page_type").val(data.page_type);

            jQuery("#ab_test_location").val(data.vgt_display_location);

            //Check the checked categories
            if (data.vgt_display_location == "on_category")
            {
                //show list of categories and also select the boxes
                vgt_check_checked_categories(JSON.parse(vgt_de_serialize_data(data.vgt_display_categories)), "#on_category_value");
                jQuery("#on_category_value").parent().fadeIn();
            }

            if (data.page_type == "squeeze")
            {
                vgt_check_checked_categories(checked_options, "#squeeze_selector");

                jQuery(".ab_page_type").parent().hide();
                jQuery("#ab_test_location").parent().hide();
                jQuery("#squeeze_selector").parent().fadeIn();

                localStorage.setItem(AB_SQUEEZE_ID, data["ab_squeeze_id"]);
                jQuery("#ab_squeeze_url").val(data["test_page_url"]);
                jQuery("#ab_squeeze_url").parent().fadeIn();
            } else

            if (data.page_type == "popup")
            {
                vgt_check_checked_categories(checked_options, "#popup_selector");

                jQuery(".ab_page_type").parent().hide();
                jQuery("#popup_selector").parent().fadeIn();
                jQuery("#ab_test_location").parent().fadeIn();
                jQuery("#ab_squeeze_url").parent().fadeOut();
            } else

            if (data.page_type == "widget")
            {
                vgt_check_checked_categories(checked_options, "#widget_selector");
                jQuery(".ab_page_type").parent().hide();
                jQuery("#widget_selector").parent().fadeIn();
                jQuery("#ab_test_location").parent().fadeIn();
                jQuery("#ab_squeeze_url").parent().fadeOut();
            }

            //description
            jQuery("#ab_test_desc").val(vgt_de_serialize_data(data.description));

            //active
            jQuery("#ab_active_option").val(data.status);


        });
    });


    //get posts categories
    vgt_get_all_categories("#on_category_value");


    //on select type of page to test
    jQuery(document).on("change", "#select_page_type", function(){
        if (jQuery(this).val() == "squeeze")
        {
            jQuery(".ab_page_type").parent().hide();
            jQuery("#ab_test_location").parent().hide();
            jQuery("#squeeze_selector").parent().fadeIn();

        } else if (jQuery(this).val() == "popup")
        {

            jQuery(".ab_page_type").parent().hide();
            jQuery("#popup_selector").parent().fadeIn();
            jQuery("#ab_test_location").parent().fadeIn();

        } else if (jQuery(this).val() == "widget")
        {

            jQuery(".ab_page_type").parent().hide();
            jQuery("#widget_selector").parent().fadeIn();
            jQuery("#ab_test_location").parent().fadeIn();

        }

    });

    //on select display location
    jQuery(document).on("change", "#ab_test_location", function(){
        if (jQuery(this).val() == "on_category")
        {
            jQuery("#on_category_value").parent().fadeIn();
        } else
        {
            jQuery("#on_category_value").parent().fadeOut();
        }
    });


    //Save the options
    jQuery(document).on("click", "#ab_create_test_btn", function(){


        var test_categories = "";

        if (jQuery("#ab_test_location").val() == "on_category")
        {
            test_categories = vgt_serialize_data(JSON.stringify(vgt_get_checked_categories("#on_category_value")));
        }

        var data = {
            action: "vgt_save_ab_test",
            ab_id: localStorage.getItem("vgt_ab_id"),
            test_name: vgt_serialize_data(jQuery("#ab_test_name").val()),
            test_location: jQuery("#ab_test_location").val(),
            test_categories: test_categories,
            page_type: jQuery("#select_page_type").val(),
            selected: vgt_serialize_data(JSON.stringify(vgt_get_checked_categories(".ab_page_type:visible"))),
            description: vgt_serialize_data(jQuery("#ab_test_desc").val()),
            status: jQuery("#ab_active_option").val()

        };

        if (jQuery("#select_page_type").val() == "squeeze")
        {
            data.ab_squeeze_id =  localStorage.getItem(AB_SQUEEZE_ID);
        }

        jQuery.post(ajaxurl, data, function(response){
            var data = vgt_parse_json_output(response);

            localStorage.setItem("vgt_ab_id", data.ab_id);

            console.log(data);
            if (data["ab_squeeze_id"] != undefined)
            {
                localStorage.setItem(AB_SQUEEZE_ID, data["ab_squeeze_id"]);
                jQuery("#ab_squeeze_url").val(data["test_page_url"]);
                jQuery("#ab_squeeze_url").parent().fadeIn();

            }

            vgt_general_notification("info", data.message, 4);

        });

    });


    //DELETE A SINGLE TEST
    jQuery(document).on("click", "#vgt_delete_ab_option", function(){

        var ab_id = localStorage.getItem("vgt_ab_id");
        var data = {
            action: "vgt_ab_delete_test",
            ab_id : ab_id,
            page_type: jQuery("#select_page_type option:selected").attr("value")
        }


        jQuery.post(ajaxurl, data, function(response){
            var data = vgt_parse_json_output(response);

            vgt_general_notification("info", data.message, 3);

            jQuery("#ab_created_test_list option[value="+ab_id+"]").remove();

            jQuery("#vgt_ab_left select, #vgt_ab_left input, #vgt_ab_left select, #vgt_ab_left input").val("");

            //reset squeeze page id if any
            localStorage.setItem(AB_SQUEEZE_ID, "0");

        });
    });


});