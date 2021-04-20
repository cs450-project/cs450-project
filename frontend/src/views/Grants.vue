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
import axios from "axios";
export default{
    name:"Grants",
    data(){
        return{
      //      grants:[],
      // HARDCODED GRANTS TO PROVE THAT THE TABLE LISTING WORKS
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
            ],
        };
    },
    /*
    methods: {
        async getGrants(){
            const {data} = await axios.get("/grants");
            this.grants=data;
        }
    },
    mounted(){
        this.getGrants();
    },*/
};
</script>