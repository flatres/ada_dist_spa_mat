(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[9],{"0ce8":function(e,t,a){"use strict";var n=a("9b6c"),l=a.n(n);l.a},"1d75":function(e,t,a){"use strict";var n=a("78c3"),l=a.n(n);l.a},"2d3a":function(e,t,a){},3239:function(e,t,a){"use strict";var n=a("b1dd"),l=a.n(n);l.a},"370d":function(e,t,a){"use strict";var n=a("2d3a"),l=a.n(n);l.a},3953:function(e,t,a){"use strict";a.r(t);var n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("toolbar-page",{attrs:{elements:e.elements}})},l=[],i=a("08e9"),s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("crud",{ref:"crud",staticStyle:{"max-width":"90vw!important"},attrs:{data:e.data,api:e.api,columns:e.columns,search:"",sortBy:"lastName",download:""}})],1)},r=[],o=a("d612"),c=a("47783"),d={getPrizes(e,t,a){c["a"].get("/academic/prizes").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},getCMPrizes(e,t,a){c["a"].get("/academic/prizes/cm").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},u={name:"ComponentJanePrizes",data(){return{api:{get:d.getPrizes},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"lastName",label:"Last Name",field:"lastName",type:"string",align:"left",filter:!0,sortable:!0},{name:"firstName",label:"First Name",field:"firstName",type:"string",align:"left"},{name:"email",label:"Pupil Email",field:"pupilEmail",type:"string",align:"left"},{name:"gender",label:"M/F",field:"gender",type:"string",align:"left"},{name:"year",label:"year",field:"NCYear",type:"string",align:"left"},{name:"ls1",label:"Salulation 2",field:"ls1",type:"string",align:"left"},{name:"email1",label:"Email 1",field:"email1",type:"string",align:"left"},{name:"ls2",label:"Salulation 2",field:"ls2",type:"string",align:"left"},{name:"email2",label:"Email 2",field:"email2",type:"string",align:"left"},{name:"txtName",label:"Prize",field:"txtName",type:"string",align:"left"},{name:"subject",label:"Subject",field:"subject",type:"string",align:"left"},{name:"txtDescription",label:"Description",field:"txtDescription",type:"string",align:"left"}],showForm:!0}},computed:{},components:{Crud:o["a"]},created(){}},p=u,f=(a("3239"),a("2be6")),m=a("e81c"),g=a("34ff"),b=a("e279"),h=a.n(b),y=Object(f["a"])(p,s,r,!1,null,"41a49c2c",null),v=y.exports;h()(y,"components",{QDialog:m["a"],QIcon:g["a"]});var j=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("crud",{ref:"crud",attrs:{data:e.data,api:e.api,columns:e.columns,search:"",sortBy:"lastName",download:""}})],1)},O=[],C={name:"ComponentJanePrizes",data(){return{api:{get:d.getCMPrizes},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"lastName",label:"Last Name",field:"lastName",type:"string",align:"left",filter:!0,sortable:!0},{name:"firstName",label:"First Name",field:"firstName",type:"string",align:"left"},{name:"count",label:"Count",field:"count",type:"string",align:"left"},{name:"email",label:"Pupil Email",field:"pupilEmail",type:"string",align:"left"},{name:"gender",label:"M/F",field:"gender",type:"string",align:"left"},{name:"year",label:"year",field:"NCYear",type:"string",align:"left"},{name:"names",label:"LS",field:"ls1",type:"string",align:"left"},{name:"email1",label:"Email 1",field:"email1",type:"string",align:"left"},{name:"names",label:"LS",field:"ls2",type:"string",align:"left"},{name:"email2",label:"Email 2",field:"email2",type:"string",align:"left"}],showForm:!0}},computed:{},components:{Crud:o["a"]},created(){}},w=C,S=(a("42c5"),Object(f["a"])(w,j,O,!1,null,"4281f0b6",null)),P=S.exports;h()(S,"components",{QDialog:m["a"],QIcon:g["a"]});var k=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("q-btn",{staticClass:"full-width",staticStyle:{height:"100px"},attrs:{outline:"",loading:e.loading,percentage:e.percentage,color:"accent"},on:{click:e.fetch},scopedSlots:e._u([{key:"loading",fn:function(){return[a("q-spinner-gears",{staticClass:"on-left"}),e._v("\n        Doing my thing...\n      ")]},proxy:!0}])},[e._v("\n      Generate\n      ")])],1),a("div",{staticClass:"row full-width full-height"},[a("console",{staticClass:"full-width full-height",staticStyle:{"min-height":"500px"}})],1)])},_=[],E=(a("e125"),a("4823"),a("2e73"),a("76d0"),a("8e9e")),x=a.n(E),N={getRegistration(e,t,a){c["a"].get("/academic/alis/registration").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postAlisGCSEUpload(e,t,a,n){var l=new FormData;l.append("file",a),c["a"].post("/academic/alis/upload/"+n,l,{headers:{"Content-Type":"multipart/form-data"}}).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},D=a("dd14"),U=a("9ce4");function F(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function A(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?F(Object(a),!0).forEach((function(t){x()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):F(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var B={name:"ComponentJanePrizes",data(){return{api:{get:N.getAlis},loading:!1,percentage:0}},methods:A(A({},Object(U["b"])("sockets",["clearConsoleLog"])),{},{fetch(){this.clearConsoleLog(),this.loading=!0,N.getRegistration(this.process,this.error)},process(e){this.loading=!1,this.$downloadBlob(e.url,e.file)},error(){this.loading=!1}}),computed:{},components:{Console:D["a"]},created(){}},$=B,Q=(a("370d"),a("2ef0")),q=a("d1dc"),z=Object(f["a"])($,k,_,!1,null,"17846a75",null),G=z.exports;h()(z,"components",{QBtn:Q["a"],QSpinnerGears:q["a"]});var L=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("h1",[e._v("GCSE")]),a("input",{ref:"gcse",attrs:{name:"file",type:"file",id:"file"}}),a("q-btn",{attrs:{label:"upload"},on:{click:function(t){return e.handleGCSEUpload()}}})],1),a("div",{staticClass:"row"},[a("h1",[e._v("Test")]),a("input",{ref:"test",attrs:{name:"file",type:"file",id:"file"}}),a("q-btn",{attrs:{label:"upload"},on:{click:function(t){return e.handleTestUpload()}}})],1)])},M=[],T={name:"ComponentJanePrizes",data(){return{gcseSpreadsheet:null,testSpreadsheet:null}},methods:{checkFileType(e){return e.filter((function(e){return".xls"===e.type}))},handleGCSEUpload(){this.gcseSpreadsheet=this.$refs.gcse.files[0];var e=0;N.postAlisGCSEUpload(this.processAlisGCSEUpload,this.$errorHandler,this.gcseSpreadsheet,e)},handleTestUpload(){this.gcseSpreadsheet=this.$refs.test.files[0];var e=1;N.postAlisGCSEUpload(this.processAlisGCSEUpload,this.$errorHandler,this.gcseSpreadsheet,e)}},computed:{},components:{},created(){N.get()}},J=T,H=(a("85b6"),Object(f["a"])(J,L,M,!1,null,"1dafb4de",null)),I=H.exports;h()(H,"components",{QBtn:Q["a"]});var R=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("h1",[e._v("Ucas Offers")]),a("input",{ref:"midyis",attrs:{name:"file",type:"file",id:"file"}}),a("q-btn",{attrs:{label:"upload"},on:{click:function(t){return e.handleUpload()}}})],1)])},Y=[],K={getUcasGrades(e,t,a){c["a"].get("/academic/ucas/grades").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))},postUcasOffersUpload(e,t,a){var n=new FormData;n.append("file",a),c["a"].post("/academic/ucas/offers/upload",n,{headers:{"Content-Type":"multipart/form-data"}}).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},V={name:"ComponentUcasupload",data(){return{spreadsheet:null}},methods:{checkFileType(e){return e.filter((function(e){return".xls"===e.type}))},handleUpload(){this.spreadsheet=this.$refs.midyis.files[0],K.postUcasOffersUpload(this.process,this.$errorHandler,this.spreadsheet)}},computed:{},components:{},created(){K.get()}},W=V,X=(a("3be4"),Object(f["a"])(W,R,Y,!1,null,"65bfe390",null)),Z=X.exports;h()(X,"components",{QBtn:Q["a"]});var ee=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("h1",[e._v("Midyis")]),a("input",{ref:"midyis",attrs:{name:"file",type:"file",id:"file"}}),a("q-btn",{attrs:{label:"upload"},on:{click:function(t){return e.handleUpload()}}})],1)])},te=[],ae={postMidyisUpload(e,t,a){var n=new FormData;n.append("file",a),c["a"].post("/academic/midyis/upload",n,{headers:{"Content-Type":"multipart/form-data"}}).then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}},ne={name:"ComponentJanePrizes",data(){return{spreadsheet:null}},methods:{checkFileType(e){return e.filter((function(e){return".xls"===e.type}))},handleUpload(){this.spreadsheet=this.$refs.midyis.files[0],ae.postMidyisUpload(this.process,this.$errorHandler,this.spreadsheet)}},computed:{},components:{},created(){ae.get()}},le=ne,ie=(a("45c0"),Object(f["a"])(le,ee,te,!1,null,"6ed49c86",null)),se=ie.exports;h()(ie,"components",{QBtn:Q["a"]});var re=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("q-btn",{staticClass:"full-width",staticStyle:{height:"50px"},attrs:{outline:"",loading:e.loading,percentage:e.percentage,color:"accent"},on:{click:e.fetch},scopedSlots:e._u([{key:"loading",fn:function(){return[a("q-spinner-gears",{staticClass:"on-left"}),e._v("\n        Doing my thing...\n      ")]},proxy:!0}])},[e._v("\n      Generate\n      ")])],1),e.loading?a("div",{staticClass:"row full-width full-height"},[a("console",{staticClass:"full-width full-height",staticStyle:{"min-height":"500px"}})],1):e._e(),a("br"),!e.loading&&e.data?a("div",[a("span",{staticClass:"q-mt-md"},[e._v(e._s(e.data.date))]),a("q-tabs",{staticClass:"text-accent bg-tertiary q-mt-xs",attrs:{dense:"",align:"left"},model:{value:e.tab,callback:function(t){e.tab=t},expression:"tab"}},[a("q-tab",{attrs:{name:"ages",iconx:"mail",label:"Ages"}}),a("q-tab",{attrs:{name:"subjects",iconx:"alarm",label:"Subjects"}})],1),a("q-tab-panels",{staticClass:"q-mt-md",attrs:{animated:""},model:{value:e.tab,callback:function(t){e.tab=t},expression:"tab"}},[a("q-tab-panel",{attrs:{name:"ages"}},[a("crud",{ref:"ages",attrs:{columns:e.ageColumns,dataOverride:e.data.ageCensus,reversex:"",download:""}})],1),a("q-tab-panel",{attrs:{name:"subjects"}},[a("crud",{ref:"ages",attrs:{columns:e.subjectColumns,dataOverride:e.data.subjects,reversex:"",download:""}})],1)],1)],1):e._e()])},oe=[],ce={getCensus(e,t,a){c["a"].get("/academic/census").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}};function de(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function ue(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?de(Object(a),!0).forEach((function(t){x()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):de(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var pe={name:"ComponentJanePrizes",data(){return{api:{get:ce.getCensus},loading:!1,percentage:0,data:null,tab:"ages",ageColumns:[{name:"id",label:"",field:"id",hidden:!0},{name:"age",label:"Age",field:"age"},{name:"boarderM",label:"Boarder (Male)",field:"boarderM",type:"string"},{name:"boarderF",label:"Boarder (Female)",field:"boarderF",type:"string"},{name:"dayM",label:"Day Pupil (Male)",field:"dayM",type:"string"},{name:"dayF",label:"Day Pupil (Female)",field:"dayF",type:"string"}],subjectColumns:[{name:"id",label:"",field:"id"},{name:"age14",label:"Age 14",field:"a_14"},{name:"age15",label:"Age 15",field:"a_15"},{name:"age16",label:"Age 16",field:"a_16"},{name:"age17",label:"Age 17",field:"a_17"},{name:"age18",label:"Age 18",field:"a_18"}]}},methods:ue(ue({},Object(U["b"])("sockets",["clearConsoleLog"])),{},{fetch(){this.clearConsoleLog(),this.loading=!0,ce.getCensus(this.process,this.error)},process(e){this.loading=!1,this.data=e},error(){this.loading=!1}}),computed:{},components:{Console:D["a"],Crud:o["a"]},created(){}},fe=pe,me=(a("1d75"),a("4776")),ge=a("dd08"),be=a("1411"),he=a("1d98"),ye=Object(f["a"])(fe,re,oe,!1,null,"6c47e1a0",null),ve=ye.exports;h()(ye,"components",{QBtn:Q["a"],QSpinnerGears:q["a"],QTabs:me["a"],QTab:ge["a"],QTabPanels:be["a"],QTabPanel:he["a"]});var je=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("loading",{attrs:{loading:e.loading,channel:"academic.meetings"}}),e.loading?e._e():a("crud",{ref:"crud",attrs:{dataOverride:e.mappedData,api:e.api,columns:e.columns,search:"",sortBy:"lastName",download:""}})],1)},Oe=[],Ce=a("0603"),we=a("10ac"),Se={name:"Component.Academic.Jane.Prizes",data(){return{data:[],loading:!1,columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"lastName",label:"Last Name",field:"lastName",type:"string",align:"left",filter:!0,sortable:!0},{name:"firstName",label:"First Name",field:"firstName",type:"string",align:"left"},{name:"email",label:"Pupil Email",field:"email",type:"string",align:"left"},{name:"gender",label:"M/F",field:"gender",type:"string",align:"left"},{name:"subject1",label:"Subject 1",field:"subject1",type:"string",sortable:!0,align:"left"},{name:"beak1",label:"Beak 1",field:"beak1",type:"string",align:"left"},{name:"subject2",label:"Subject 2",field:"subject2",type:"string",sortable:!0,align:"left"},{name:"beak2",label:"Beak 2",field:"beak2",type:"string",align:"left"},{name:"subject3",label:"Subject 3",field:"subject3",type:"string",sortable:!0,align:"left"},{name:"beak3",label:"Beak 3",field:"beak3",type:"string",align:"left"},{name:"subject4",label:"Subject 4",field:"subject4",type:"string",sortable:!0,align:"left"},{name:"beak4",label:"Beak 4",field:"beak4",type:"string",align:"left"},{name:"subject5",label:"Subject 5",field:"subject5",type:"string",sortable:!0,align:"left"},{name:"beak5",label:"Beak 5",field:"beak5",type:"string",align:"left"},{name:"subject6",label:"Subject 6",field:"subject6",type:"string",sortable:!0,align:"left"},{name:"beak6",label:"Beak 6",field:"beak6",type:"string",align:"left"},{name:"parent1",label:"P1",field:"parent1",type:"string",align:"left"},{name:"parent1Email",label:"P1 Email",field:"parent1Email",type:"string",align:"left"},{name:"parent2",label:"P2",field:"parent2",type:"string",align:"left"},{name:"parent2Email",label:"P2 Email",field:"parent2Email",type:"string",align:"left"}],showForm:!0}},computed:{mappedData(){return this.data.map((function(e){var t={id:e.id,firstName:e.firstName,lastName:e.lastName,gender:e.gender,email:e.email,parent1:"",parent1Email:"",parent2:"",parent2Email:"",subject1:e.subject1,subject2:e.subject2,subject3:e.subject3,subject4:e.subject4,subject5:e.subject5,subject6:e.subject6,beak1:e.beak1,beak2:e.beak2,beak3:e.beak3,beak4:e.beak4,beak5:e.beak5,beak6:e.beak6};return e.contacts[0]&&(t.parent1="".concat(e.contacts[0]["title"]," ").concat(e.contacts[0]["lastName"]),t.parent1Email=e.contacts[0].email),e.contacts[1]&&(t.parent2="".concat(e.contacts[1]["title"]," ").concat(e.contacts[1]["lastName"]),t.parent2Email=e.contacts[1].email),t}))}},components:{Crud:o["a"],Loading:we["a"]},created(){var e=this;this.loading=!0,Ce["a"].getAllMeetings((function(t){console.log("here"),e.data=t.appointments,e.loading=!1}))}},Pe=Se,ke=(a("6f56"),Object(f["a"])(Pe,je,Oe,!1,null,"74099800",null)),_e=ke.exports;h()(ke,"components",{QDialog:m["a"],QIcon:g["a"]});var Ee=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("loading",{attrs:{loading:e.loading,channel:"academic.meetings"}}),e.loading?e._e():a("crud",{ref:"crud",attrs:{dataOverride:e.data,columns:e.columns,search:"",sortBy:"lastName",download:""}})],1)},xe=[],Ne={name:"Component.Academic.Jane.SchoolsCloud",data(){return{data:[],loading:!1,columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"firstName",label:"Teacher Firstname",field:"firstName",type:"string",align:"left",filter:!0,sortable:!0},{name:"lastName",label:"Teacher Surname",field:"lastName",type:"string",align:"left"},{name:"subject",label:"Subject",field:"subject",type:"string",align:"left"},{name:"code",label:"Class Code",field:"code",type:"string",align:"left"},{name:"misId",label:"Class External ID",field:"misId",type:"string",sortable:!0,align:"left"},{name:"studentFirstName",label:"Student Firstname",field:"studentFirstName",type:"string",align:"left"},{name:"studentLastName",label:"Student Surname",field:"studentLastName",type:"string",sortable:!0,align:"left"},{name:"reg",label:"Student Registration Class",field:"reg",type:"string",align:"left"},{name:"dob",label:"Student Date of Birth",field:"dob",type:"string",align:"left"}],showForm:!0}},computed:{},components:{Crud:o["a"],Loading:we["a"]},created(){var e=this;this.loading=!0,Ce["a"].getSchoolsCloud((function(t){e.data=t,e.loading=!1}))}},De=Ne,Ue=(a("0ce8"),Object(f["a"])(De,Ee,xe,!1,null,"4ac6e0d8",null)),Fe=Ue.exports;h()(Ue,"components",{QDialog:m["a"]});var Ae=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("q-btn",{staticClass:"full-width",staticStyle:{height:"100px"},attrs:{outline:"",loading:e.loading,percentage:e.percentage,color:"accent"},on:{click:e.fetch},scopedSlots:e._u([{key:"loading",fn:function(){return[a("q-spinner-gears",{staticClass:"on-left"}),e._v("\n        Doing my thing...\n      ")]},proxy:!0}])},[e._v("\n      Generate\n      ")])],1),a("div",{staticClass:"row full-width full-height"},[a("console",{staticClass:"full-width full-height",staticStyle:{"min-height":"500px"}})],1)])},Be=[],$e={getAlmanacHistory(e,t,a){c["a"].get("/academic/almanac/history").then((function(t){e(t.data)})).catch((function(e){console.log(e)}))}};function Qe(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function qe(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?Qe(Object(a),!0).forEach((function(t){x()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):Qe(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var ze={name:"ComponentJanePrizes",data(){return{api:{get:$e.getAlis},loading:!1,percentage:0}},methods:qe(qe({},Object(U["b"])("sockets",["clearConsoleLog"])),{},{fetch(){this.clearConsoleLog(),this.loading=!0,$e.getAlmanacHistory(this.process,this.error)},process(e){this.loading=!1,this.$downloadBlob(e.url,e.file)},error(){this.loading=!1}}),computed:{},components:{Console:D["a"]},created(){}},Ge=ze,Le=(a("a3c3"),Object(f["a"])(Ge,Ae,Be,!1,null,"24cbd0a3",null)),Me=Le.exports;h()(Le,"components",{QBtn:Q["a"],QSpinnerGears:q["a"]});var Te=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{staticClass:"row"},[a("q-btn",{staticClass:"full-width",staticStyle:{height:"100px"},attrs:{outline:"",loading:e.loading,percentage:e.percentage,color:"accent"},on:{click:e.fetch},scopedSlots:e._u([{key:"loading",fn:function(){return[a("q-spinner-gears",{staticClass:"on-left"}),e._v("\n        Doing my thing...\n      ")]},proxy:!0}])},[e._v("\n      Generate\n      ")])],1),a("div",{staticClass:"row full-width full-height"},[a("console",{staticClass:"full-width full-height",staticStyle:{"min-height":"500px"}})],1)])},Je=[];function He(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,n)}return a}function Ie(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?He(Object(a),!0).forEach((function(t){x()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):He(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var Re={name:"ComponentJanePrizes",data(){return{api:{get:K.getAlis},loading:!1,percentage:0}},methods:Ie(Ie({},Object(U["b"])("sockets",["clearConsoleLog"])),{},{fetch(){this.clearConsoleLog(),this.loading=!0,K.getUcasGrades(this.process,this.error)},process(e){this.loading=!1,this.$downloadBlob(e.url,e.file)},error(){this.loading=!1}}),computed:{},components:{Console:D["a"]},created(){}},Ye=Re,Ke=(a("6f3e"),Object(f["a"])(Ye,Te,Je,!1,null,"b4184efc",null)),Ve=Ke.exports;h()(Ke,"components",{QBtn:Q["a"],QSpinnerGears:q["a"]});var We={name:"PageLabCrud",data(){return{elements:[{name:"prizes",label:"prizes",component:v,shortcut:"b"},{name:"CMPrizes",label:"C/M Prizes",component:P,shortcut:"b"},{name:"alis",label:"alis",component:G,shortcut:"b"},{name:"alisUpload",label:"Alis Upload",component:I,shortcut:"b"},{name:"midyisUpload",label:"Midyis Upload",component:se,shortcut:"b"},{name:"ucasOffersUpload",label:"UCAS Offers Upload",component:Z,shortcut:"b"},{name:"census",label:"census",component:ve,shortcut:"b"},{name:"meetings",label:"meetings",component:_e,shortcut:"b"},{name:"schoolsCloud",label:"Schools Cloud",component:Fe,shortcut:"b"},{name:"almanac",label:"Almanac",component:Me,shortcut:"b"},{name:"ucas",label:"UCAS",component:Ve,shortcut:"b"}]}},components:{toolbarPage:i["a"]}},Xe=We,Ze=Object(f["a"])(Xe,n,l,!1,null,null,null);t["default"]=Ze.exports},"3be4":function(e,t,a){"use strict";var n=a("fe21"),l=a.n(n);l.a},"42c5":function(e,t,a){"use strict";var n=a("7762"),l=a.n(n);l.a},"45c0":function(e,t,a){"use strict";var n=a("554b"),l=a.n(n);l.a},"554b":function(e,t,a){},5784:function(e,t,a){},"6f3e":function(e,t,a){"use strict";var n=a("5784"),l=a.n(n);l.a},"6f56":function(e,t,a){"use strict";var n=a("a56b"),l=a.n(n);l.a},7762:function(e,t,a){},"78c3":function(e,t,a){},7934:function(e,t,a){},"85b6":function(e,t,a){"use strict";var n=a("a470"),l=a.n(n);l.a},"9b6c":function(e,t,a){},a3c3:function(e,t,a){"use strict";var n=a("7934"),l=a.n(n);l.a},a470:function(e,t,a){},a56b:function(e,t,a){},b1dd:function(e,t,a){},fe21:function(e,t,a){}}]);