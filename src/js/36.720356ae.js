(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[36],{"8c5c":function(t,e,s){},d7e1:function(t,e,s){"use strict";var n=s("8c5c"),i=s.n(n);i.a},edef:function(t,e,s){"use strict";s.r(e);var n=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-layout",{attrs:{view:"hHh lpR fFf"}},[s("q-header",{staticClass:"bg-primary text-white",staticStyle:{"border-bottom":"1px solid black"},attrs:{elevatedx:""}},[s("q-toolbar",{staticClass:"bg-grey-4"},[s("q-toolbar-title",{staticClass:"justify-center"},[s("h2",{staticClass:"q-mt-none q-mb-xs text-center text-black",staticStyle:{"font-size":"30px",height:"38px"}},[t._v("Coach "),s("span",{staticClass:"text-bold text-black"},[t._v(t._s(t.code))])]),s("p",{staticClass:"text-center text-black q-mb-xs"},[t._v(t._s(t.register.length)+" students : "+t._s(t.spacesLeft)+" spaces remaining")])])],1)],1),s("q-page-container",{staticClass:"q-pb-xl"},[t.qrOpen||t.addOpen?t._e():s("q-page-sticky",{staticClass:"z-max",attrs:{position:"bottom-left",offset:[18,18]}},[s("q-fab",{attrs:{"vertical-actions-align":"left",color:"warning",icon:"fal fa-plus",direction:"up",position:"bottom-left"},model:{value:t.fab,callback:function(e){t.fab=e},expression:"fab"}},[t.addOpen?t._e():s("q-fab-action",{attrs:{"label-position":"right",color:t.isFull?"grey-5":"primary",icon:"fal fa-user",label:t.isFull?"Full":"Add Pupil"},on:{click:function(e){t.addOpen=!t.isFull}}}),s("q-fab-action",{attrs:{"label-position":"right",color:"primary",icon:"fal fa-camera",label:"Scan Tickets"},on:{click:function(e){t.qrOpen=!0}}})],1)],1),!0===t.qrOpen?s("q-page-sticky",{staticClass:"z-max",attrs:{position:"bottom-left",offset:[18,18]}},[s("q-btn",{attrs:{color:"warning",round:"",icon:"fal fa-arrow-left"},on:{click:function(e){t.qrOpen=!1}}})],1):t._e(),!0===t.qrOpen?s("q-page-sticky",{staticClass:"z-max",attrs:{position:"top",offset:[0,-65]}},[s("div",{staticClass:"row full-width bg-white window-width bd-font"},[s("p",{staticClass:"text-center text-black q-mb-xs text-bold text-center full-width ",staticStyle:{"font-size":"20px"}},[t._v(t._s(t.registeredCount)+" / "+t._s(t.register.length))])])]):t._e(),s("q-dialog",{attrs:{maximized:""},model:{value:t.qrOpen,callback:function(e){t.qrOpen=e},expression:"qrOpen"}},[s("qrcode-stream",{attrs:{track:t.paintBoundingBox},on:{init:t.onInit,decode:t.onDecode}})],1),s("div",{staticClass:"flex-center full-width"},[s("q-list",{staticClass:"flex-center",staticStyle:{"max-width":"400px",margin:"auto"}},t._l(t.register,(function(e){return 0===e.isRegistered?s("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],key:e.id,attrs:{tag:"label"}},[s("q-item-section",[s("q-item-label",{staticClass:"text-left"},[t._v(t._s(e.details.displayName))]),s("q-item-label",{staticClass:"text-left",attrs:{caption:""}},[t._v(t._s(e.details.boardingHouse)+" - "+t._s(e.stop))])],1),s("q-item-section",{attrs:{side:"",top:""}},[s("q-btn-toggle",{staticClass:"toggle",attrs:{spread:"","no-caps":"",unelevated:"","toggle-color":"dark",color:"white","text-color":"dark",options:[{label:"No",value:0},{label:"Yes",value:1}]},on:{input:function(s){return t.registerStudent(e)}},model:{value:e.isRegistered,callback:function(s){t.$set(e,"isRegistered",s)},expression:"student.isRegistered"}})],1)],1):t._e()})),1)],1),s("h2",{staticClass:"text-center",staticStyle:{"font-size":"28px"}},[t._v("Registered")]),s("div",{staticClass:"flex-center full-width"},[s("q-list",{staticClass:"flex-center",staticStyle:{"max-width":"400px",margin:"auto"}},t._l(t.register,(function(e){return 1===e.isRegistered?s("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],key:e.id,attrs:{tag:"label"}},[s("q-item-section",[s("q-item-label",{staticClass:"text-left"},[t._v(t._s(e.details.displayName))]),s("q-item-label",{staticClass:"text-left",attrs:{caption:""}},[t._v(t._s(e.details.boardingHouse)+" - "+t._s(e.stop))])],1),s("q-item-section",{attrs:{side:"",top:""}},[s("q-btn-toggle",{staticClass:"toggle",attrs:{spread:"","no-caps":"",unelevated:"","toggle-color":"dark",color:"white","text-color":"dark",options:[{label:"No",value:0},{label:"Yes",value:1}]},on:{input:function(s){return t.registerStudent(e)}},model:{value:e.isRegistered,callback:function(s){t.$set(e,"isRegistered",s)},expression:"student.isRegistered"}})],1)],1):t._e()})),1)],1)],1),s("q-dialog",{attrs:{maximized:""},model:{value:t.addOpen,callback:function(e){t.addOpen=e},expression:"addOpen"}},[s("q-layout",{staticClass:"bg-white",attrs:{view:"hHh lpR fFf"}},[s("q-header",{staticClass:"bg-primary text-white",attrs:{elevated:""}},[s("q-toolbar",{staticClass:"items-center row"},[s("q-toolbar-title",[s("q-icon",{attrs:{name:"fad fa-user"}}),s("span",{staticClass:"on-right"},[t._v("Add Pupil")])],1),s("q-space"),s("q-btn",{attrs:{label:"cancel",outline:"",color:"white"},on:{click:t.cancelAdd}})],1)],1),s("q-page-container",{staticClass:"bg-white"},[t.newHouse?t._e():s("div",{staticClass:"fitx flex row items-start justify-around content-stretch q-mt-md"},t._l(t.houses,(function(e){return s("q-btn",{key:e.code,staticClass:"q-mb-xs text-bold text-center items-center bg-grey-4",staticStyle:{width:"45vw",height:"15vw"},attrs:{flat:"",color:"",outline:"","content-class":"text-red"},on:{click:function(s){t.newHouse=e}}},[s("span",{staticClass:"full-width text-bold items-center"},[t._v(t._s(e.code))])])})),1),t.newHouse&&!t.newYear?s("div",{staticClass:"fitx flex row items-start justify-around content-stretch q-mt-md"},t._l(t.years,(function(e){return s("q-btn",{key:e.value,staticClass:"q-mb-xs text-bold text-center items-center col-12 bg-grey-4",staticStyle:{width:"100vw",height:"15vw"},attrs:{flat:"",color:"",outline:"","content-class":"text-red"},on:{click:function(s){t.newYear=e}}},[s("span",{staticClass:"full-width text-bold items-center"},[t._v(t._s(e.label))])])})),1):t._e(),t.newHouse&&t.newYear&&!t.newStudent?s("div",{staticClass:"fitx flex row items-start justify-around content-stretch q-mt-md"},t._l(t.filteredStudents,(function(e){return s("q-btn",{key:e.id,staticClass:"q-mb-xs text-bold text-center items-center col-12 bg-grey-4",staticStyle:{width:"100vw",height:"15vw"},attrs:{disabled:t.isOnCoach(e),flat:"",color:"",outline:"","content-class":"text-red"},on:{click:function(s){return t.setStudent(e)}}},[s("span",{staticClass:"full-width text-bold items-center"},[t._v(t._s(e.lastName)+", "+t._s(e.firstName)+" "+t._s(t.isOnCoach(e)?" (booked)":""))])])})),1):t._e(),t.newHouse&&t.newYear&&t.newStudent?s("div",{staticClass:"fitx flex row items-start justify-around content-stretch q-mt-md"},t._l(t.stops,(function(e){return s("q-btn",{key:e.id,staticClass:"q-mb-xs text-bold text-center items-center col-12 bg-grey-4",staticStyle:{width:"100vw",height:"15vw"},attrs:{flat:"",color:"",outline:"","content-class":"text-red"},on:{click:function(s){return t.confirmAddStudent(e)}}},[s("span",{staticClass:"full-width text-bold items-center"},[t._v(t._s(e.name))])])})),1):t._e()])],1),t.newStop?s("q-dialog",{model:{value:t.confirmAddStudentOpen,callback:function(e){t.confirmAddStudentOpen=e},expression:"confirmAddStudentOpen"}},[s("q-card",[s("q-card-section",{staticClass:"row"},[s("span",{staticClass:"full-width text-bold items-center"},[t._v(t._s(t.newStudent.lastName)+", "+t._s(t.newStudent.firstName))]),s("span",{staticClass:"full-width text-bold items-center"},[t._v(t._s(t.newStop.name))])]),s("q-card-section",{staticClass:"row"},[s("q-btn",{attrs:{color:"black",label:"Confirm",size:"lg"},on:{click:t.addStudent}}),s("q-btn",{staticClass:"on-right",attrs:{color:"black",label:"Cancel",size:"lg"},on:{click:function(e){t.confirmAddStudentOpen=!1}}})],1)],1)],1):t._e()],1)],1)},i=[],a=(s("2e73"),s("5e32"),s("4823"),s("9ebb"),s("c880"),s("a7e1"),s("76d0"),s("4fb0"),s("93db")),o=s.n(a),r=(s("df26"),s("5965")),l=s.n(r),c=s("47783"),d={getCoachRegister(t,e,s){c["a"].get("/aux/bookings/coach/register/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},putCoachRegister(t,e,s){c["a"].put("/aux/bookings/coach/register",s).then((function(e){t(e.data)})).catch((function(t){console.log(t),e()}))},getHouses(t,e,s){c["a"].get("/aux/bookings/coach/houses/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getStudents(t,e,s){c["a"].get("/aux/bookings/coach/students/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},postBooking(t,e,s){c["a"].post("/aux/bookings/coach/booking",s).then((function(e){t(e.data)})).catch((function(t){console.log(t),e()}))}},u=s("9973");function f(t,e){var s;if("undefined"===typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(s=h(t))||e&&t&&"number"===typeof t.length){s&&(t=s);var n=0,i=function(){};return{s:i,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:i}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,o=!0,r=!1;return{s:function(){s=t[Symbol.iterator]()},n:function(){var t=s.next();return o=t.done,t},e:function(t){r=!0,a=t},f:function(){try{o||null==s.return||s.return()}finally{if(r)throw a}}}}function h(t,e){if(t){if("string"===typeof t)return p(t,e);var s=Object.prototype.toString.call(t).slice(8,-1);return"Object"===s&&t.constructor&&(s=t.constructor.name),"Map"===s||"Set"===s?Array.from(t):"Arguments"===s||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(s)?p(t,e):void 0}}function p(t,e){(null==e||e>t.length)&&(e=t.length);for(var s=0,n=new Array(e);s<e;s++)n[s]=t[s];return n}var g={name:"Aux.Bookings.Coach",props:{},data(){return{id:null,uniqueId:null,routeId:null,code:null,capacity:null,stops:null,register:[],spacesLeft:null,addOpen:!1,houses:[],students:[],newHouse:null,newYear:null,newStudent:null,newStop:null,qrOpen:!1,confirmAddStudentOpen:!1,fab:!1,loading:!1,years:[{label:"Shell",value:9},{label:"Remove",value:10},{label:"Hundred",value:11},{label:"Lower Sixth",value:12},{label:"Upper Sixth",value:13}]}},methods:{setStudent(t){this.isOnCoach(t)||(this.newStudent=t)},isOnCoach(t){return this.register.find((function(e){return e.studentId===t.id}))},confirmAddStudent(t){this.newStop=t,this.confirmAddStudentOpen=!0},addStudent(){this.$q.loading.show();var t={uniqueId:this.uniqueId,studentId:this.newStudent.id,stopId:this.newStop.id,routeId:this.routeId};d.postBooking(this.processBooking,this.$errorHandler,t)},processBooking(t){this.confirmAddStudentOpen=!1,this.$q.loading.hide();var e=this.newStudent.lastName+", "+this.newStudent.firstName;this.$q.notify({message:e,color:"green",icon:"fas fa-user-check",position:"center"}),this.getRegister(),this.cancelAdd()},process(t){var e=this;this.id=t.id,this.code=t.code,this.capacity=t.capacity,this.register=t.register,this.spacesLeft=t.spacesLeft,this.routeId=t.routeId,this.stops=t.stops,d.getHouses((function(t){e.houses=t}),this.$errorHandler,this.uniqueId),d.getStudents((function(t){e.students=t}),this.$errorHandler,this.uniqueId)},getRegister(){d.getCoachRegister(this.process,this.$errorHandler,this.uniqueId)},registerStudent(t){t.coachUniqueId=this.uniqueId,d.putCoachRegister(null,this.$errorHandler,t)},cancelAdd(){this.newHouse=null,this.newYear=null,this.addOpen=!1,this.newStudent=null,this.newStop=null},paintBoundingBox(t,e){var s,n=f(t);try{for(n.s();!(s=n.n()).done;){var i=s.value,a=i.boundingBox,o=a.x,r=a.y,l=a.width,c=a.height;e.lineWidth=3,e.strokeStyle="#ff0000",e.strokeRect(o,r,l,c)}}catch(d){n.e(d)}finally{n.f()}},onInit(t){var e=this;return l()(o.a.mark((function s(){return o.a.wrap((function(s){while(1)switch(s.prev=s.next){case 0:return e.loading=!0,e.$q.loading.show(),s.prev=2,s.next=5,t;case 5:s.next=10;break;case 7:s.prev=7,s.t0=s["catch"](2),console.error(s.t0);case 10:return s.prev=10,e.loading=!1,e.$q.loading.hide(),s.finish(10);case 14:case"end":return s.stop()}}),s,null,[[2,7,10,14]])})))()},onDecode(t){var e=this;this.result=t,console.log("decoded",t);var s=t.split("-");if(2===s.length){var n=s[0],i=s[1];if(n===this.id.toString())if(this.register.find((function(t){return t.studentId.toString()===i}))){var a=this.register.find((function(t){return t.studentId.toString()===i}));a.coachUniqueId=this.uniqueId,a.isRegistered=1,this.$q.loading.show(),d.putCoachRegister((function(t){e.$q.loading.hide(),e.$q.notify({message:a.details.displayName,color:"green",icon:"fas fa-user-check",position:"center"})}),this.$errorHandler,a)}else this.$q.notify({message:"Not listed on this coach",color:"red",icon:"fas fa-skull-crossbones",position:"center"});else this.$q.notify({message:"Wrong coach",color:"red",icon:"fas fa-skull-crossbones",position:"center"})}else this.$q.notify({message:"Invalid QR Code",color:"red",icon:"fas fa-skull-crossbones",position:"center"})}},computed:{isFull(){return 0===this.spacesLeft},filteredStudents(){var t=this;return console.log(this.newHouse,this.newYear),this.newHouse&&this.newYear?this.students.filter((function(e){return e.year===t.newYear.value&&e.houseId===t.newHouse.id})):[]},registeredCount(){return this.register.filter((function(t){return 1===t.isRegistered})).length}},watch:{},components:{QrcodeStream:u["QrcodeStream"]},created(){this.uniqueId=this.$route.params.id,this.uniqueId&&(this.getRegister(),this.$wampSubscribe("aux.coaches.register."+this.uniqueId,this.getRegister))}},b=g,m=(s("d7e1"),s("2be6")),w=s("b4af"),x=s("5c88"),q=s("eb05"),v=s("f85a"),y=s("2ce9"),S=s("4f38"),C=s("6799"),k=s("cd4d"),_=s("2ef0"),I=s("e81c"),O=s("6c93"),R=s("ac9b"),H=s("66dc"),$=s("7d9a"),A=s("6dd6"),Q=s("34ff"),N=s("f962c"),B=s("ebe6"),Y=s("965d"),z=s("2be8"),j=s("e279"),F=s.n(j),L=Object(m["a"])(b,n,i,!1,null,"1872ab3b",null);e["default"]=L.exports;F()(L,"components",{QLayout:w["a"],QHeader:x["a"],QToolbar:q["a"],QToolbarTitle:v["a"],QPageContainer:y["a"],QPageSticky:S["a"],QFab:C["a"],QFabAction:k["a"],QBtn:_["a"],QDialog:I["a"],QList:O["a"],QItem:R["a"],QItemSection:H["a"],QItemLabel:$["a"],QBtnToggle:A["a"],QIcon:Q["a"],QSpace:N["a"],QCard:B["a"],QCardSection:Y["a"]}),F()(L,"directives",{Ripple:z["a"]})}}]);