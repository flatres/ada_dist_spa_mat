(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["app"],{0:function(n,e,t){n.exports=t("2f39")},"1e5d":function(n,e,t){},"2f39":function(n,e,t){"use strict";t.r(e);var r={};t.r(r),t.d(r,"userId",function(){return Cn}),t.d(r,"name",function(){return Dn}),t.d(r,"permissions",function(){return Tn}),t.d(r,"isDark",function(){return Rn}),t.d(r,"isLight",function(){return xn}),t.d(r,"isAuthenticated",function(){return Fn}),t.d(r,"getModuleAccess",function(){return Pn}),t.d(r,"getModuleColor",function(){return On}),t.d(r,"getPageAccess",function(){return _n});var o={};t.r(o),t.d(o,"authUser",function(){return Mn}),t.d(o,"clearAuthData",function(){return Wn}),t.d(o,"isDark",function(){return jn}),t.d(o,"role",function(){return Bn});var a={};t.r(a),t.d(a,"login",function(){return Un}),t.d(a,"tryAutoLogin",function(){return qn}),t.d(a,"logout",function(){return Gn}),t.d(a,"isDark",function(){return Jn}),t.d(a,"setAxiosHeader",function(){return Nn}),t.d(a,"setPrimaryColor",function(){return zn});var s={};t.r(s),t.d(s,"consoleLog",function(){return Yn}),t.d(s,"updaterData",function(){return Zn}),t.d(s,"updater",function(){return ne}),t.d(s,"progress",function(){return ee});var i={};t.r(i),t.d(i,"consoleLog",function(){return te}),t.d(i,"updater",function(){return re}),t.d(i,"progress",function(){return oe}),t.d(i,"clearConsoleLog",function(){return ae});var u={};t.r(u),t.d(u,"connectSocket",function(){return ie}),t.d(u,"clearConsoleLog",function(){return ue});var c={};t.r(c),t.d(c,"activeSession",function(){return fe}),t.d(c,"coachOutFilter",function(){return de}),t.d(c,"coachRetFilter",function(){return pe});var l={};t.r(l),t.d(l,"setActiveSession",function(){return he}),t.d(l,"setCoachOutFilter",function(){return me}),t.d(l,"setCoachRetFilter",function(){return be});t("ac6a"),t("a114"),t("d14b"),t("df75"),t("1e5d"),t("7e6d");var f=t("2b0e"),d=t("65b7"),p=t("e84f"),h=t("7051"),m=t("2040"),b=t("cf12"),g=t("46a9"),v=t("93f5"),Q=t("32a1"),A=t("f30c"),L=t("ce67"),k=t("482e"),I=t("1731"),w=t("52b5"),S=t("1180"),y=t("1e55"),C=t("506f"),D=t("b8d9"),T=t("7d43"),R=t("79e9"),x=t("5d8b"),F=t("9e58"),P=t("9541"),O=t("c563"),_=t("db7b"),E=t("c081"),H=t("525b"),M=t("9413"),W=t("c604"),j=t("62a9"),B=t("66d7"),V=t("7b38"),$=t("53fe"),U=t("6186"),q=t("5931"),G=t("0ed2"),J=t("b5b8"),N=t("6aa0"),z=t("53fc"),K=t("f6fb"),X=t("866b"),Y=t("b70a"),Z=t("895f"),nn=t("c3fc"),en=t("e4f9"),tn=t("03d8"),rn=t("0388"),on=t("bc9b"),an=t("0952"),sn=t("1526"),un=t("2bd2"),cn=t("133b"),ln=t("6780"),fn=t("f9d8"),dn=t("f2eb");f["a"].use(p["a"],{config:{},iconSet:d["a"],components:{QLayout:h["a"],QLayoutHeader:m["a"],QLayoutDrawer:b["a"],QPageContainer:g["a"],QScrollArea:v["a"],QPage:Q["a"],QToolbar:A["a"],QToolbarTitle:L["a"],QBtn:k["a"],QBtnGroup:I["a"],QIcon:w["a"],QList:S["a"],QListHeader:y["a"],QItem:C["a"],QItemMain:D["a"],QItemSide:T["a"],QField:R["a"],QInput:x["a"],QBtnDropdown:F["a"],QItemTile:P["a"],QTabs:O["a"],QTab:_["a"],QTabPane:E["a"],QCheckbox:H["a"],QRouteTab:M["a"],QTable:W["a"],QTh:j["a"],QTr:B["a"],QTd:V["a"],QTableColumns:$["a"],QSearch:U["a"],QSelect:q["a"],QPopupEdit:G["a"],QPopover:J["a"],QFab:N["a"],QFabAction:z["a"],QColorPicker:K["a"],QAjaxBar:X["a"],QSpinner:Y["a"],QSpinnerGears:Z["a"],QSpinnerAudio:nn["a"],QTree:en["a"],QTooltip:tn["a"],QDialog:rn["a"],QToggle:on["a"],QModal:an["a"]},directives:{Ripple:sn["a"],CloseOverlay:un["a"]},plugins:{Notify:cn["a"],Dialog:ln["a"],LocalStorage:fn["a"],AppFullscreen:dn["a"]}});var pn=function(){var n=this,e=n.$createElement,t=n._self._c||e;return t("div",{attrs:{id:"q-app"}},[t("router-view"),t("q-ajax-bar",{attrs:{color:"primary"}})],1)},hn=[];pn._withStripped=!0;t("a481");var mn={name:"App",data:function(){return{dark:!1}},methods:{},created:function(){var n=this;this.$store.dispatch("user/tryAutoLogin").then(function(e){!0===e||n.$router.replace("/login")})}},bn=mn,gn=(t("7faf"),t("2877")),vn=Object(gn["a"])(bn,pn,hn,!1,null,null,null);vn.options.__file="App.vue";var Qn=vn.exports,An=t("2f62"),Ln={},kn=t("a709"),In=t("8d6f"),wn=t("5781"),Sn={namespaced:!0,state:Ln,getters:kn,mutations:In,actions:wn},yn={userId:null,auth:null,firstname:null,lastname:null,permissions:{},roles:[],isDark:!0,isLight:!1},Cn=function(n){return n.userId},Dn=function(n){var e={firstname:n.firstname,lastname:n.lastname};return e},Tn=function(n){return n.permissions},Rn=function(n){return n.isDark},xn=function(n){return n.isLight},Fn=function(n){return null!==n.auth},Pn=function(n){return function(e){return!!n.permissions[e]&&n.permissions[e].hasAccess}},On=function(n){return function(e){return n.permissions[e]?n.permissions[e].color:"#4fc08d"}},_n=function(n){return function(e,t){if(!n.permissions[e])return!1;var r=n.permissions[e];return!!r.pages[t]&&r.pages[t].hasAccess}},En=(t("7514"),t("278c")),Hn=t.n(En),Mn=(t("cadf"),t("ffc1"),function(n,e){n.auth=e.auth,n.userId=e.userId,n.permissions=e.permissions,n.roles=e.roles}),Wn=function(n){n.auth=null,n.userId=null,n.permissions={},n.roles=[]},jn=function(n,e){n.isDark=e,n.isLight=!e},Bn=function(n,e){var t=e[0].data,r=e[0].roleId;Object.entries(t).forEach(function(e){var o=Hn()(e,2),a=o[0],s=o[1];if(n.permissions[a]){!0===t[a].hasAccess&&(n.permissions[a].hasAccess=!0,n.permissions[a].fromRoles.find(function(n){return n===r})||n.permissions[a].fromRoles.push(r)),!1===t[a].hasAccess&&1===n.permissions[a].fromRoles.length&&(n.permissions[a].fromRoles=[],n.permissions[a].hasAccess=!1);var i=s.pages;Object.entries(i).forEach(function(e){var t=Hn()(e,2),o=t[0],s=t[1];n.permissions[a].pages[o]?n.permissions[a].pages[o].fromRoles.find(function(n){return n===r})?1===n.permissions[a].pages[o].fromRoles.length&&f["a"].set(n.permissions[a].pages,o,s):!0===s.hasAccess&&(n.permissions[a].pages[o].hasAccess=!0,n.permissions[a].pages[o].fromRoles.push(r)):f["a"].set(n.permissions[a].pages,o,s)})}else f["a"].set(n.permissions,a,s)})},Vn=(t("551c"),t("be3b")),$n=t("fb82"),Un=function(n,e){var t=n.commit,r=n.dispatch;return console.log(e),new Promise(function(n,o){Vn["a"].post("/auth/login",{login:e.login,password:e.password}).then(function(e){var o=e.data;t("authUser",{auth:o.auth,userId:o.userId,permissions:o.permissions,roles:o.roles}),fn["a"].set("auth",o.auth),fn["a"].set("userId",o.userId),fn["a"].set("permissions",o.permissions),fn["a"].set("roles",o.roles),r("setAxiosHeader",e.data.auth),r("sockets/connectSocket",o.auth,{root:!0}),n(e)}).catch(function(n){o(n)})})},qn=function(n){var e=n.commit,t=n.dispatch,r=fn["a"].get.item("auth");return null!==r&&(e("authUser",{auth:r,userId:fn["a"].get.item("userId"),permissions:fn["a"].get.item("permissions"),roles:fn["a"].get.item("roles")}),t("setAxiosHeader",r),t("sockets/connectSocket",r,{root:!0}),!0)},Gn=function(n){var e=n.commit,t=n.dispatch;fn["a"].clear(),e("clearAuthData"),t("setAxiosHeader","")},Jn=function(n,e){var t=n.commit;t("isDark",e)},Nn=function(n,e){Vn["a"].defaults.headers.common["Authorization"]=e},zn=function(n,e){n.commit;return e===$n["a"]},Kn={namespaced:!0,state:yn,getters:r,mutations:o,actions:a},Xn={consoleLog:[],updaters:{},updaterData:{},progress:{},progressIsComplete:{}},Yn=(t("20d6"),function(n){return n.consoleLog.slice().reverse()}),Zn=function(n){return function(e){return n.updaterData[e]}},ne=function(n){return function(e,t){if(!n.updaters[e])return t;var r=n.updaters[e],o=r.key,a=r.data[o],s=t.findIndex(function(n){return n[o]===a});return s>-1&&f["a"].set(t,s,r.data),t}},ee=function(n){return function(e){return n.progress[e]?n.progress[e]:0}},te=function(n,e){if(!0===e.replace){for(var t=n.consoleLog.length,r=t-1;r>=0;r--)if(!1===n.consoleLog[r].isError){n.consoleLog[r].message=e.message;break}}else n.consoleLog.push(e),console.log(e.message)},re=function(n,e){var t=e[0].updaterId;f["a"].set(n.updaters,t,e[0])},oe=function(n,e){var t=e[0].progressId;f["a"].set(n.progress,t,e[0].progress),f["a"].set(n.progressIsComplete,t,e[0].isComplete)},ae=function(n){n.consoleLog=[]},se=t("5300"),ie=function(n,e){var t=n.commit,r=n.rootState;f["a"].use(se["a"],{debug:!0,url:"ws://localhost:9090",realm:"realm1",onopen:function(n,e){console.log("WAMP connected",n,e)},onclose:function(n,e){console.log("WAMP closed: "+n,e)}}),f["a"].Wamp.subscribe("console_"+e,function(n,e,r){t("consoleLog",n[0])},{acknowledge:!0}).then(function(n){}),f["a"].Wamp.subscribe("updater_"+e,function(n,e,r){t("updater",n)},{acknowledge:!0}).then(function(n){}),f["a"].Wamp.subscribe("progress_"+e,function(n,e,r){t("progress",n)},{acknowledge:!0}).then(function(n){}),r.user.roles.forEach(function(n,e){console.log(n),f["a"].Wamp.subscribe("role_"+n,function(n,e,r){t("user/role",n,{root:!0})},{acknowledge:!0}).then(function(n){})})},ue=function(n){var e=n.commit;e("clearConsoleLog")},ce={namespaced:!0,state:Xn,getters:s,mutations:i,actions:u},le={activeSession:null,activeSessionName:null,coachOutFilter:[],coachRetFilter:[]},fe=function(n){return n.activeSession},de=function(n){return n.coachOutFilter},pe=function(n){return n.coachRetFilter},he=function(n,e){n.activeSession=e},me=function(n,e){n.coachOutFilter=e},be=function(n,e){n.coachRetFilter=e},ge=t("f80c"),ve={namespaced:!0,state:le,getters:c,mutations:l,actions:ge};f["a"].use(An["a"]);var Qe=new An["a"].Store({modules:{example:Sn,user:Kn,sockets:ce,transport:ve}}),Ae=Qe,Le=t("8c4f"),ke={path:"admin",component:function(){return t.e("78a70bb5").then(t.bind(null,"1fc9"))},children:[{path:"",component:function(){return t.e("2d0c8bc5").then(t.bind(null,"55c2"))}},{path:"active",component:function(){return t.e("0cce225a").then(t.bind(null,"d2f6"))}},{path:"sync",component:function(){return t.e("009bb327").then(t.bind(null,"cbc7"))}},{path:"access",component:function(){return t.e("04a6d9cb").then(t.bind(null,"7328"))}}]},Ie={path:"transport",component:function(){return t.e("594acb35").then(t.bind(null,"337a"))},children:[{path:"",component:function(){return t.e("dc23fb7c").then(t.bind(null,"a65a"))}}]},we={path:"students",component:function(){return t.e("35e7f440").then(t.bind(null,"8109"))},children:[{path:"profile",component:function(){return t.e("17dcbf5a").then(t.bind(null,"fa06"))}},{path:"list",component:function(){return t.e("c1a3d554").then(t.bind(null,"923e"))}}]},Se=[{path:"/",component:function(){return t.e("d12bd58c").then(t.bind(null,"7b3d"))},children:[{path:"",component:function(){return t.e("571d61bc").then(t.bind(null,"9261"))}},{path:"exams",component:function(){return t.e("46ff0965").then(t.bind(null,"9dbe"))},children:[{path:"",component:function(){return t.e("2d0abcb4").then(t.bind(null,"1791"))}},{path:"gcse",component:function(){return Promise.all([t.e("64bdd70f"),t.e("0ebcc7c2")]).then(t.bind(null,"3e17"))}},{path:"alevel",component:function(){return Promise.all([t.e("64bdd70f"),t.e("a45e68c6")]).then(t.bind(null,"1224"))}}]},{path:"locations",component:function(){return t.e("e40d3636").then(t.bind(null,"d6e3"))},children:[{path:"chapel",component:function(){return t.e("865eede8").then(t.bind(null,"2cf0"))}}]},{path:"user",component:function(){return t.e("fdfed406").then(t.bind(null,"f98a"))}},ke,Ie,we]},{path:"/login",name:"login",component:function(){return t.e("2ce7b370").then(t.bind(null,"33d1"))},children:[{path:"",component:function(){return t.e("3ce25fa8").then(t.bind(null,"c6f7"))}}]},{path:"*",component:function(){return t.e("4b4818b8").then(t.bind(null,"ee5d"))}}],ye=Se;f["a"].use(Le["a"]);var Ce=new Le["a"]({mode:"history",base:"/",scrollBehavior:function(){return{y:0}},routes:ye}),De=Ce,Te=function(){var n="function"===typeof Ae?Ae():Ae,e="function"===typeof De?De({store:n}):De;n.$router=e;var t={el:"#q-app",router:e,store:n,render:function(n){return n(Qn)}};return{app:t,store:n,router:e}},Re=function(n){var e=n.router;n.store,n.Vue;e.beforeEach(function(n,e,t){t()})},xe=(t("b55d"),t("38db"),function(n){n.app,n.router,n.Vue}),Fe=t("558c"),Pe=t.n(Fe),Oe=function(n){n.app,n.router;var e=n.Vue;e.use(Pe.a)},_e=Te(),Ee=_e.app,He=_e.store,Me=_e.router;[Vn["b"],Re,xe,Oe].forEach(function(n){n({app:Ee,router:Me,store:He,Vue:f["a"],ssrContext:null})}),new f["a"](Ee)},5781:function(n,e){},6292:function(n,e,t){},"7e6d":function(n,e,t){},"7faf":function(n,e,t){"use strict";var r=t("6292"),o=t.n(r);o.a},"8d6f":function(n,e){},a709:function(n,e){},be3b:function(n,e,t){"use strict";t.d(e,"a",function(){return a});var r=t("bc3a"),o=t.n(r),a=o.a.create({baseURL:"http://localhost/api/v1/public"});e["b"]=function(n){var e=n.Vue;e.prototype.$axios=a}},f80c:function(n,e){}},[[0,"runtime","vendor"]]]);