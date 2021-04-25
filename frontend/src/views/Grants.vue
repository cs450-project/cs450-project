<template> 
<div>
    <h1> Grants </h1>
    <!--USED TO SORT GRANTS IN TABLE FORM-->
    <table border = '1'>
        <tr>
            <td> Grant Name </td>
            <td> Grant Source </td>
        </tr>
        <!--LIST ASPECTS OF EACH GRANT IN EACH ROW-->
        <tr v-for="(grant, i) in grants" :key="i">
            <td> {{grant.name}} </td>
            <td> {{grant.type}}</td>
        </tr>
    </table>
</div>
</template>

<script> 
//import axios from "axios";
import Vue from "vue";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default{
    name:"Grants",
    data(){
        return{
            // Can not figure out how to map data and showcase it here.
           grants:{},
      // HARDCODED GRANTS TO PROVE THAT THE TABLE LISTING WORKS
      /*
      grants:[
                {
                    name:"Grant 1",
                    type: "NSF"
                },
                {
                    name:"Grant 2",
                    type: "ODU"
                },
                {
                    name:"Grant 3",
                    type: "VT"
                }
            ],*/
        };
    },
    computed:{
        ...mapGetters(["errorMsg", "GrantOptions"]),
    },
    methods: {
        ...mapMutations(["setToken", "setError"]),
        ...mapActions(["register", "getGrants"]),
    },
    mounted(){
        if(this.getGrants){
            this.grants=this.getGrants;
        }
    },
    created(){
        this.getGrants();
    }
};
</script>