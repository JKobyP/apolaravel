module.exports = function (Resources) {

    return Resources.form.extend({

        data: function () {
            return {
                form: {
                    id: -1,
                    brothers: [],
                    event_name: '',
                    description: '',
                    event_date: '',
                    type: '',
                    location: ''
                },
                users : [],
                report: [],
                method:'PUT'
            }
        },
        methods: {
            successFunction: function (data) {
                $(this.$$.loadingArea).collapse('hide');
                //collapse modal, update data in main list
                this.$dispatch('successfulEdit',data['data']);
                //Update the existing report reference to use this data.
                this.startOver();
                $(this.$$.modal).modal('hide');
            },
            setupUserSearch: function () {
                Resources.User(this).get({},function(data,status,response){
                    if(status == 200){
                        this.users = data;
                        var that = this;
                        var selector = $('#brotherselecter');
                        selector.select2(Resources.select2settings(data,that.formatBrother));
                        selector.on("select2:select", function(e){
                            //get the name of the thing selected.
                            if(!that.hasBrother(e)){
                                that.addBrother(e);
                            }
                            selector.val(null).trigger("change");
                        });
                    } else {
                        console.log("error: "+data);
                    }
                })
            },
            getIDForForm: function(){
              return this.form.id;
            },
            getForm: function () {
                var newForm = JSON.parse(JSON.stringify(this.form))
                return newForm;
            },
            formatBrother: function(brother){
                if(brother.nickname !== null){
                    return brother.nickname + ' ('+brother.first_name+') '+brother.last_name;
                } else {
                    return brother.first_name + ' ' +brother.last_name;
                }
            },
            hasBrother: function(e){
                var broListing = e.params.data;
                var length = this.form.brothers.length;
                for(var i=0; i<length; i++){
                    if(this.form.brothers[i].id == broListing.id){
                        return true;
                    }
                }
                return false;
            },
            addBrother: function(e){
                var broListing = e.params.data;
                console.log(this.formatBrother(broListing));
                var newBro = {
                    'id' : broListing.id,
                    'name' : this.formatBrother(broListing),
                    'hours' : 0,
                    'minutes' : 0
                };
                this.form.brothers.push(newBro);
            },
            removeBrother: function(brother){
                this.form.brothers.$remove(brother);

            },
            clearForm: function(){
                this.form = {
                    brothers: [],
                    event_name: '',
                    description: '',
                    event_date: '',
                    type: '',
                    location: ''
                };
            },
            show: function(){
                $(this.$$.modal).modal('show');
            }
        },
        filters: {
            isFalse: function (val) {
                return val === '0';
            }
        },
        ready: function () {
            this.setupUserSearch();
        }
    });
}

