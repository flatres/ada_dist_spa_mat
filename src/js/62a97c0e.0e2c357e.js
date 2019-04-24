(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["62a97c0e"],{"08e9":function(e,t,n){"use strict";var s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",{staticClass:"no-scroll"},[n("q-toolbar",{staticClass:"bg-toolbar text-white shadow-2 rounded-borders narrowx justify",attrs:{dense:"",shrink:""}},[n("q-tabs",{attrs:{dense:"",shrink:"","indicator-color":"primary"},model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.elements,function(t){return n("div",{key:t.name},[t.menu?e._e():n("q-tab",{attrs:{label:t.label,name:t.name}}),t.menu?n("q-btn",{directives:[{name:"hotkey",rawName:"v-hotkey",value:{"shift+a":function(){e.selectedTab="bookings"}},expression:"{'shift+a': () => { selectedTab = 'bookings' }}"}],attrs:{flat:"",label:"Admin",icon:"fal fa-caret-down"},nativeOn:{click:function(n){n.stopPropagation(),e.selectedTab=t.name}}},[n("q-menu",{ref:"settingsPopover",refInFor:!0,attrs:{"content-class":"bg-dark text-white","auto-close":""}},[n("q-list",{attrs:{"item-separator":"",link:"","content-class":"bg-primary"}},e._l(t.menu,function(t){return n("q-item",{key:t.name,attrs:{clickable:""},nativeOn:{click:function(n){e.selectedTab=t.name}}},[n("q-item-section",{attrs:{avatar:"",left:"",dark:""}},[n("q-icon",{attrs:{name:t.icon+" fa-sm"}})],1),n("q-item-section",[n("q-item-label",[e._v(e._s(t.label))])],1)],1)}),1)],1)],1):e._e()],1)}),0),n("q-space"),e._t("side")],2),n("q-tab-panels",{model:{value:e.selectedTab,callback:function(t){e.selectedTab=t},expression:"selectedTab"}},e._l(e.tabPanels,function(t){return n("q-tab-panel",{key:t.name,attrs:{name:t.name}},[n(t.component,{tag:"component",on:{close:function(t){e.selectedTab=null}}})],1)}),1)],1)},a=[],o=(n("c880"),n("2e73"),{name:"ComponentPageToolbar",props:{default:null,elements:null},watch:{selectedTab:function(){console.log(":",this.selectedTab)}},data:function(){return{selectedTab:null}},computed:{tabPanels:function(){var e=[];return this.elements.forEach(function(t){t.menu?t.menu.forEach(function(t){e.push({name:t.name,component:t.component})}):e.push({name:t.name,component:t.component})}),e}},created:function(){this.selectedTab=this.default}}),c=o,l=n("2be6"),i=Object(l["a"])(c,s,a,!1,null,null,null);t["a"]=i.exports},"0c4f":function(e,t,n){"use strict";n.r(t);var s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("toolbar-page",{attrs:{elements:e.elements,default:"chat"}})},a=[],o=n("08e9"),c=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"row"},[n("p",{staticClass:"text-secondary"},[e._v("\n      Uses the socket server directy (i.e no api calls) to subscribe / publish\n    ")]),n("q-space"),n("p",[e._v("\n      "+e._s(e.time)+"\n      "),n("span",[e._v(" ms")])])],1),n("div",{staticClass:"row"},[n("p",{staticClass:"text-secondary"},[e._v("\n      "+e._s(e.server)+"\n    ")])]),n("div",{staticClass:"row q-px-md"},[n("div",{staticClass:"col-6 "},[n("q-input",{staticClass:"bg-black text-green",attrs:{outlined:"",dark:"",type:"textarea",autogrow:""},on:{keyup:e.keyUp},model:{value:e.sendText,callback:function(t){e.sendText=t},expression:"sendText"}})],1),n("div",{staticClass:"col-6 q-pl-lg"},[n("q-input",{staticClass:"bg-black text-green",attrs:{outlined:"",dark:"",type:"textarea",autogrow:"",readonly:""},model:{value:e.receiveText,callback:function(t){e.receiveText=t},expression:"receiveText"}})],1)])])},l=[],i={name:"PageLabSockets",data:function(){return{wamp:null,time:"Time of flight",wampSubscription:null,sendText:"",receiveText:"",sendTime:0,receiveTime:0,channel:"lab.sockets.chat",server:""}},components:{},methods:{keyUp:function(e){console.log("K:",e.key),this.sendTime=Date.now(),this.$wamp.publish(this.channel,[],{key:e.key},{exclude_me:!1})}},mounted:function(){this.server="wss://adazmq.marlboroughcollege.org/wss",this.wamp=this.$wamp.subscribe(this.channel,function(e,t,n){var s=t.key;this.receiveTime=Date.now(),this.time=this.receiveTime-this.sendTime,1===s.length?this.receiveText+=s:"Backspace"===s&&(this.receiveText=this.receiveText.slice(0,-1))},{acknowledge:!0}).then(function(e){this.sendText="",this.wampSubscription=e})}},r=i,u=n("2be6"),d=Object(u["a"])(r,c,l,!1,null,null,null),m=d.exports,p=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",[n("div",{staticClass:"row"},[n("p",{staticClass:"text-secondary"},[e._v("\n      Uses ZeroMQ Transport to send content from a running script to the Ratchet (Websocket) server. Tests the console and progress channels.\n    ")]),n("q-space"),n("p",[e._v("\n      "+e._s(e.time)+"\n      "),n("span",[e._v(" ms")])])],1),n("div",{staticClass:"row q-mb-md"},[n("p",{staticClass:"text-secondary"},[e._v("\n      "+e._s(e.server)+" ["+e._s(e.zmqServer)+"] ("+e._s(e.consoleProgress)+")\n    ")]),n("q-space"),n("q-btn",{staticStyle:{width:"100px"},attrs:{color:"primary",dark:"",outline:""},on:{click:e.clear}},[e._v("Clear\n   ")])],1),n("div",{staticClass:"row q-mb-lg"},[n("q-btn",{staticStyle:{width:"100%"},attrs:{loading:e.loading,percentage:e.consoleProgress,color:"primary",dark:"",outline:""},on:{click:e.start},scopedSlots:e._u([{key:"loading",fn:function(){return[n("q-spinner-gears",{staticClass:"on-left"}),e._v("\n         Computing...\n       ")]},proxy:!0}])},[e._v("\n       Compute PI\n       ")])],1),n("div",{staticClass:"row q-px-md"},[n("div",{staticClass:"col"},[n("console",{staticStyle:{"min-height":"50vh"}})],1)])])},b=[],h=n("b506"),f=n.n(h),v=n("9ce4"),g=n("dd14"),k=n("4778"),_={postStartConsole:function(e,t,n){k["a"].post("/lab/sockets/console",n).then(function(t){e(t.data)}).catch(function(e){console.log(e),t&&t()})},getZMQ:function(e,t,n){k["a"].get("/lab/sockets/zmq").then(function(t){e(t.data)}).catch(function(e){console.log(e)})}},x={name:"PageLabConsole",data:function(){return{loading:!1,server:null,zmqServer:" - "}},components:{console:g["a"]},methods:f()({},Object(v["b"])("sockets",["clearConsoleLog"]),{start:function(){var e=this;this.loading=!0,_.postStartConsole(function(){e.loading=!1})},clear:function(){this.loading=!1,this.clearConsoleLog()}}),computed:f()({},Object(v["c"])("sockets",["progress"]),{consoleProgress:function(){return this.progress("Lab.Sockets.Console")}}),mounted:function(){var e=this;this.server="wss://adazmq.marlboroughcollege.org/wss",_.getZMQ(function(t){console.log(t),e.zmqServer=t})}},w=x,T=Object(u["a"])(w,p,b,!1,null,null,null),q=T.exports,C={name:"PageLabSockets",data:function(){return{elements:[{name:"chat",label:"Chat",component:m,shortcut:"c"},{name:"console",label:"Console",component:q,shortcut:"s"}]}},components:{toolbarPage:o["a"]},methods:{refresh:function(){this.$root.$emit("crud_refresh")}}},y=C,S=Object(u["a"])(y,s,a,!1,null,null,null);t["default"]=S.exports},"11c1":function(e,t,n){},d277:function(e,t,n){"use strict";var s=n("11c1"),a=n.n(s);a.a},dd14:function(e,t,n){"use strict";var s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{staticClass:"console q-ma-md full-height"},[n("p",{staticClass:"q-ma-sm"},[e._v("Console")]),n("ul",e._l(e.consoleLog,function(t){return n("li",{key:t.lineIndex,class:{error:t.isError,indent1:1==t.indent,indent2:2==t.indent,indent3:3==t.indent}},[e._v("\n      "+e._s(t.message)+"\n    ")])}),0)])},a=[],o=n("b506"),c=n.n(o),l=n("9ce4"),i={name:"AppConsole",data:function(){return{}},computed:c()({},Object(l["c"])("sockets",["consoleLog"]))},r=i,u=(n("d277"),n("2be6")),d=Object(u["a"])(r,s,a,!1,null,"99bdb7ea",null);t["a"]=d.exports}}]);