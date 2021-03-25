(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[35],{1085:function(t,e,s){},9519:function(t,e,s){"use strict";var a=s("1085"),o=s.n(a);o.a},e6d3:function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.hash&&t.details.lastName?s("div",{staticClass:"fixed-top text-center q-ma-sm q-mb-xl"},[s("q-scroll-area",{staticClass:"q-pb-xl",staticStyle:{height:"98vh","max-width":"400px",margin:"auto"}},[s("div",{staticClass:"row text-center q-mt-xl flex-center"},[s("q-icon",{attrs:{size:"100px",color:"green-8",name:"fad fa-biohazard"}})],1),s("div",{staticClass:"row text-center q-mt-md flex-center text-h7 text-bold q-mb-none"},[t._v("\n      "+t._s(t.details.lastName)+", "+t._s(t.details.prename)+"\n    ")]),s("div",{staticClass:"row text-center q-mt-xs flex-center text-overline text-italic"},[t._v("\n      "+t._s(t.details.datePretty)+"\n    ")]),t.successOpen?t._e():s("div",[t.hasAnswered?s("div",[s("div",{staticClass:"row text-center q-mt-xs flex-center text-overline"},[t._v("\n          Answers already submitted\n        ")])]):t._e(),t.hasTakenTestOpen?s("div",{staticClass:"text-blue-10"},[s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Have you completed an at-home Covid test?\n        ")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline q-gutter-x-md"},[s("q-btn",{staticClass:"col",class:t.hasTakenTest?"":"bg-blue-10 text-white",attrs:{color:t.hasTakenTest?"blue-10":"white",flat:!t.hasTakenTest,outline:t.hasTakenTest,label:"no"},on:{click:function(e){t.hasTakenTest=!1}}}),s("q-btn",{staticClass:"col",class:t.hasTakenTest?"bg-blue-10 text-white":"",attrs:{color:t.hasTakenTest?"white":"blue-10",flat:t.hasTakenTest,outline:!t.hasTakenTest,label:"yes"},on:{click:function(e){t.hasTakenTest=!0}}})],1),t.hasTakenTest?s("div",{staticClass:"q-mb-lg q-mt-sm q-ml-mdX"},[s("h3",{staticClass:"q-mt-md q-mb-none  text-h6 text-left"},[t._v("Have you logged your result with NHS Test and Trace?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"blue-10",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.hasLoggedTest,callback:function(e){t.hasLoggedTest=e},expression:"hasLoggedTest"}})],1),!1===t.hasLoggedTest?s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold text-red"},[t._v("\n            Please log your result "),s("a",{staticClass:"q-ml-sm",attrs:{href:"https://www.gov.uk/report-covid19-result",target:"blank"}},[t._v("here")]),t._v(" and confirm that you have done this.\n          ")]):t._e(),!0===t.hasLoggedTest?s("div",[s("h3",{staticClass:"q-mt-md q-mb-none  text-h6 text-left"},[t._v("Was your at-home Covid test negative?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"blue-10",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.testWasNegative,callback:function(e){t.testWasNegative=e},expression:"testWasNegative"}})],1),!1===t.testWasNegative?s("div",{staticClass:"q-mt-md"},[s("div",{staticClass:"row"},[t._v("Please do not come into work and you should contact your line manager(s) as soon as possible. Click on the link below to arrange a test (NHS Link).")]),s("q-btn",{staticClass:"q-mt-lg",attrs:{outline:"",color:"red",type:"a",href:"https://www.nhs.uk/conditions/coronavirus-covid-19/testing-and-tracing/get-a-test-to-check-if-you-have-coronavirus/",target:"_blank",label:"nhs.uk"}})],1):t._e()]):t._e()]):t._e()]):t._e(),t.comingToWorkOpen&&(!0===t.hasTakenTest&&!0===t.hasLoggedTest&&!0===t.testWasNegative||!1===t.hasTakenTest)?s("div",[s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Are you coming in to work on campus today?\n        ")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline q-gutter-x-md"},[s("q-btn",{staticClass:"text-green col",attrs:{label:"no",outline:""},on:{click:t.notAtWork}}),s("q-btn",{staticClass:"text-green col",attrs:{label:"yes",outline:""},on:{click:t.atWork}})],1)]):t._e(),t.submitOpen||!0===t.hasTakenTest&&!1===t.testWasNegative?s("div",[t.msg?s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold"},[t._v("\n          "+t._s(t.msg)+"\n        ")]):t._e(),t.questionsOpen&&!1===t.hasTakenTest||t.questionsOpen&&!0===t.hasTakenTest&&!1!==t.testWasNegative?s("div",{staticClass:"q-mb-lg q-mt-sm"},[s("q-separator"),s("h3",{staticClass:"q-mt-md q-mb-none  text-h6 text-left"},[t._v("Have you felt hot or cold or shivery?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question1,callback:function(e){t.question1=e},expression:"question1"}})],1),s("h3",{staticClass:"text-h6 text-left q-mt-md q-mb-none"},[t._v("Do you have a new persistent cough? This means coughing a lot for more than an hour.")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question2,callback:function(e){t.question2=e},expression:"question2"}})],1),s("h3",{staticClass:"text-h6 text-left q-mt-md q-mb-none"},[t._v("Do you have a loss of smell or taste?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question3,callback:function(e){t.question3=e},expression:"question3"}})],1),s("h3",{staticClass:"text-h6 text-left q-mt-md q-mb-none"},[t._v("Have you had recent contact with anyone ill with Covid?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question4,callback:function(e){t.question4=e},expression:"question4"}})],1),s("q-separator")],1):t._e(),t.disabled?t._e():s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Submit your answers?\n        ")]),t.disabled?s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Please answer all questions\n        ")]):t._e(),s("div",{staticClass:"row text-left q-mt-xs q-mb-xl flex-center text-overline q-gutter-x-md"},[s("q-btn",{staticClass:"text-red col",attrs:{label:"back",outline:""},on:{click:t.goBack}}),s("q-btn",{staticClass:"text-green col",attrs:{label:"yes",outline:"",disabled:t.disabled},on:{click:t.submit}})],1)]):t._e()]),!t.successOpen||!t.healthy&&t.comingToWork?t._e():s("div",[t._v("\n      Thank you\n    ")]),t.successOpen&&!t.healthy&&t.comingToWork?s("div",[s("div",{staticClass:"row"},[t._v("Please do not come into work and you should contact your line manager(s) as soon as possible. Click on the link below to arrange a test (NHS Link).")]),s("q-btn",{staticClass:"q-mt-lg",attrs:{outline:"",color:"red",type:"a",href:"https://www.nhs.uk/conditions/coronavirus-covid-19/testing-and-tracing/get-a-test-to-check-if-you-have-coronavirus/",target:"_blank",label:"nhs.uk"}})],1):t._e()])],1):t._e()},o=[],l=s("d2b5"),n={name:"Aux.Covid.Staff",props:{},data(){return{details:{},comingToWork:!0,comingToWorkOpen:!0,hasAnswered:!1,submitOpen:!1,questionsOpen:!1,msg:null,question1:null,question2:null,question3:null,question4:null,successOpen:!1,hasTakenTestOpen:!0,hasTakenTest:!1,hasLoggedTest:null,testWasNegative:null}},methods:{goBack(){this.msg=null,this.comingToWorkOpen=!0,this.submitOpen=!1},atWork(){this.questionsOpen=!0,this.submitOpen=!0,this.comingToWork=!0,this.comingToWorkOpen=!1},notAtWork(){this.msg="You are not coming on to campus today.",this.comingToWork=!1,this.comingToWorkOpen=!1,this.questionsOpen=!1,this.submitOpen=!0},process(t){this.details=t,t.hasAnswered&&(this.hasAnswered=!0,this.comingToWorkOpen=!1,this.hasTakenTestOpen=!1)},submit(){var t={};t.isNotInWork=!this.comingToWork,!1===this.testWasNegative&&this.hasTakenTest&&(t.isNotInWork=!0),t.isHealthy=this.healthy,t.hasTakenTest=this.hasTakenTest,t.hasLoggedTest=this.hasLoggedTest,t.testWasNegative=this.testWasNegative,t.hash=this.hash,l["a"].postStaffResponse(this.success,this.$errorHandler,t)},success(){this.successOpen=!0}},computed:{healthy(){return(!0!==this.hasTakenTest||!1!==this.testWasNegative)&&(!!this.comingToWork&&(!1===this.question1&&!1===this.question2&&!1===this.question3&&!1===this.question4))},hash(){return this.$route.query.h},disabled(){if(!0===this.hasTakenTest&&!1===this.hasLoggedTest)return!0;if(!0===this.hasTakenTest&&!1===this.testWasNegative)return!1;if(!1===this.comingToWork)return!1;if(!0===this.comingToWork){if(null===this.question1)return!0;if(null===this.question2)return!0;if(null===this.question3)return!0;if(null===this.question4)return!0}}},components:{},created(){l["a"].getStaffDetails(this.process,this.errorHandler,this.hash)}},i=n,r=(s("9519"),s("2be6")),c=s("26a8"),h=s("34ff"),u=s("2ef0"),d=s("6dd6"),x=s("96f0"),v=s("e279"),g=s.n(v),m=Object(r["a"])(i,a,o,!1,null,"3e901004",null);e["default"]=m.exports;g()(m,"components",{QScrollArea:c["a"],QIcon:h["a"],QBtn:u["a"],QBtnToggle:d["a"],QSeparator:x["a"]})}}]);