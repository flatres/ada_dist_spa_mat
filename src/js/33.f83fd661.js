(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[33],{"217f":function(t,e,s){"use strict";var o=s("388f"),n=s.n(o);n.a},"388f":function(t,e,s){},e6d3:function(t,e,s){"use strict";s.r(e);var o=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"fixed-top text-center q-ma-sm q-mb-xl"},[s("q-scroll-area",{staticClass:"q-pb-xl",staticStyle:{height:"98vh","max-width":"400px",margin:"auto"}},[s("div",{staticClass:"row text-center q-mt-xl flex-center"},[s("q-icon",{attrs:{size:"100px",color:"green-8",name:"fad fa-biohazard"}})],1),s("div",{staticClass:"row text-center q-mt-md flex-center text-h7 text-bold q-mb-none"},[t._v("\n      "+t._s(t.details.lastName)+", "+t._s(t.details.prename)+"\n    ")]),s("div",{staticClass:"row text-center q-mt-xs flex-center text-overline text-italic"},[t._v("\n      "+t._s(t.details.datePretty)+"\n    ")]),t.successOpen?t._e():s("div",[t.hasAnswered?s("div",[s("div",{staticClass:"row text-center q-mt-xs flex-center text-overline"},[t._v("\n          Answers already submitted\n        ")])]):t._e(),t.comingToWorkOpen?s("div",[s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Are you coming to work today?\n        ")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline q-gutter-x-md"},[s("q-btn",{staticClass:"text-green col",attrs:{label:"no",outline:""},on:{click:t.notAtWork}}),s("q-btn",{staticClass:"text-green col",attrs:{label:"yes",outline:""},on:{click:t.atWork}})],1)]):t._e(),t.submitOpen?s("div",[t.msg?s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold"},[t._v("\n          "+t._s(t.msg)+"\n        ")]):t._e(),t.questionsOpen?s("div",{staticClass:"q-mb-lg q-mt-sm"},[s("h3",{staticClass:"q-mt-md q-mb-none  text-h6 text-left"},[t._v("Have you felt hot or cold or shivery?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question1,callback:function(e){t.question1=e},expression:"question1"}})],1),s("h3",{staticClass:"text-h6 text-left q-mt-md q-mb-none"},[t._v("Do you have a new persistent cough?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question2,callback:function(e){t.question2=e},expression:"question2"}})],1),s("h3",{staticClass:"text-h6 text-left q-mt-md q-mb-none"},[t._v("Do you have a loss of smell or taste?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question3,callback:function(e){t.question3=e},expression:"question3"}})],1),s("h3",{staticClass:"text-h6 text-left q-mt-md q-mb-none"},[t._v("Have you had recent contact with anyone ill with Covid?")]),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline text-bold full-width"},[s("q-btn-toggle",{staticClass:"full-width no-shadow",staticStyle:{border:"1px solid black"},attrs:{spread:"","no-caps":"","toggle-color":"green",flatx:"",color:"white","text-color":"black",options:[{label:"No",value:!1},{label:"Yes",value:!0}]},model:{value:t.question4,callback:function(e){t.question4=e},expression:"question4"}})],1),s("q-separator")],1):t._e(),t.disabled?t._e():s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Submit your answers?\n        ")]),t.disabled?s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline"},[t._v("\n          Please answer all questions\n        ")]):t._e(),s("div",{staticClass:"row text-left q-mt-xs flex-center text-overline q-gutter-x-md"},[s("q-btn",{staticClass:"text-red col",attrs:{label:"back",outline:""},on:{click:t.goBack}}),s("q-btn",{staticClass:"text-green col",attrs:{label:"yes",outline:"",disabled:t.disabled},on:{click:t.submit}})],1)]):t._e()]),t.successOpen?s("div",[t._v("\n      Have a nice day\n    ")]):t._e()])],1)},n=[],l=s("4778"),i={getStaffDetails:function(t,e,s){l["a"].get("/aux/covid/staff/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postStaffResponse:function(t,e,s){l["a"].post("/aux/covid/staff",s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getStudentDetails:function(t,e,s){l["a"].get("/aux/covid/student/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postStudentResponse:function(t,e,s){l["a"].post("/aux/covid/student",s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},a={name:"Aux.Covid.Staff",props:{},data:function(){return{details:{},comingToWork:!0,comingToWorkOpen:!0,hasAnswered:!1,submitOpen:!1,questionsOpen:!1,msg:null,question1:null,question2:null,question3:null,question4:null,successOpen:!1}},methods:{goBack:function(){this.msg=null,this.comingToWorkOpen=!0,this.submitOpen=!1},atWork:function(){this.questionsOpen=!0,this.submitOpen=!0,this.comingToWork=!0,this.comingToWorkOpen=!1},notAtWork:function(){this.msg="Your are not coming to work today.",this.comingToWork=!1,this.comingToWorkOpen=!1,this.questionsOpen=!1,this.submitOpen=!0},process:function(t){this.details=t,t.hasAnswered&&(this.hasAnswered=!0,this.comingToWorkOpen=!1)},submit:function(){var t={};t.isNotInWork=!this.comingToWork,t.isHealthy=this.healthy,t.hash=this.hash,i.postStaffResponse(this.success,this.$errorHandler,t)},success:function(){this.successOpen=!0}},computed:{healthy:function(){return!!this.comingToWork&&(!1===this.question1&&!1===this.question2&&!1===this.question3&&!1===this.question4)},hash:function(){return this.$route.query.h},disabled:function(){if(!1===this.comingToWork)return!1;if(!0===this.comingToWork){if(null===this.question1)return!0;if(null===this.question2)return!0;if(null===this.question3)return!0;if(null===this.question4)return!0}}},components:{},created:function(){i.getStaffDetails(this.process,this.errorHandler,this.hash)}},c=a,r=(s("217f"),s("2be6")),u=s("26a8"),d=s("34ff"),f=s("2ef0"),x=s("6dd6"),h=s("96f0"),m=s("e279"),v=s.n(m),q=Object(r["a"])(c,o,n,!1,null,"58aa7908",null);e["default"]=q.exports;v()(q,"components",{QScrollArea:u["a"],QIcon:d["a"],QBtn:f["a"],QBtnToggle:x["a"],QSeparator:h["a"]})}}]);