(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["app"],{0:function(n,e,t){n.exports=t("7d3e")},"0afc":function(n,e,t){},"1c72":function(n,e,t){"use strict";var r=t("ff1e"),o=t.n(r);o.a},"40e4":function(n,e){},"476c":function(n,e){},5516:function(n,e,t){"use strict";t.d(e,"a",function(){return a});var r=t("7338"),o=t.n(r),a=o.a.create({baseURL:Object({NODE_ENV:"production",CLIENT:!0,SERVER:!1,DEV:!1,PROD:!0,THEME:"mat",MODE:"spa",VUE_ROUTER_MODE:"history",VUE_ROUTER_BASE:"/",APP_URL:"undefined"}).API_URL+"/api/v1/public"});o.a.defaults.port=80,e["b"]=function(n){var e=n.Vue;e.prototype.$axios=a}},"564f":function(n,e,t){},"7d3e":function(n,e,t){"use strict";t.r(e);var r={};t.r(r),t.d(r,"userId",function(){return kn}),t.d(r,"name",function(){return Tn}),t.d(r,"permissions",function(){return On}),t.d(r,"isDark",function(){return _n}),t.d(r,"isLight",function(){return wn}),t.d(r,"isAuthenticated",function(){return Pn}),t.d(r,"getModuleAccess",function(){return yn}),t.d(r,"getModuleColor",function(){return Cn}),t.d(r,"getPageAccess",function(){return Un});var o={};t.r(o),t.d(o,"authUser",function(){return Fn}),t.d(o,"clearAuthData",function(){return Mn}),t.d(o,"isDark",function(){return Hn}),t.d(o,"role",function(){return Nn});var a={};t.r(a),t.d(a,"login",function(){return Wn}),t.d(a,"tryAutoLogin",function(){return $n}),t.d(a,"logout",function(){return qn}),t.d(a,"isDark",function(){return Gn}),t.d(a,"setAxiosHeader",function(){return Jn}),t.d(a,"setPrimaryColor",function(){return zn});var s={};t.r(s),t.d(s,"consoleLog",function(){return Yn}),t.d(s,"updaterData",function(){return Zn}),t.d(s,"updater",function(){return ne}),t.d(s,"progress",function(){return ee});var i={};t.r(i),t.d(i,"consoleLog",function(){return te}),t.d(i,"updater",function(){return re}),t.d(i,"progress",function(){return oe}),t.d(i,"clearConsoleLog",function(){return ae});var u={};t.r(u),t.d(u,"connectSocket",function(){return ie}),t.d(u,"clearConsoleLog",function(){return ue});var c={};t.r(c),t.d(c,"activeSession",function(){return le}),t.d(c,"coachOutFilter",function(){return pe}),t.d(c,"coachRetFilter",function(){return de});var f={};t.r(f),t.d(f,"setActiveSession",function(){return me}),t.d(f,"setCoachOutFilter",function(){return he}),t.d(f,"setCoachRetFilter",function(){return be});t("5b54"),t("3b86"),t("cb5f"),t("f850"),t("0afc"),t("564f");var l=t("e832"),p=t("2c52"),d=t("c66d"),m=t("36d4"),h=t("272c"),b=t("cfb1"),g=t("5418"),v=t("3f02"),Q=t("99be"),E=t("f416"),A=t("1340"),R=t("d9b0"),L=t("a8f5"),I=t("d7f5"),D=t("af77"),S=t("7bf7"),k=t("03e6"),T=t("0ffe"),O=t("6e6d"),_=t("fb08"),w=t("86fd"),P=t("85e5"),y=t("48d9"),C=t("6af1"),U=t("8bd5"),V=t("4b12"),x=t("22d0"),F=t("d6c5"),M=t("22ba"),H=t("9bbe"),N=t("4e8c"),j=t("992b"),B=t("aa8e"),W=t("3a46"),$=t("0e36"),q=t("a261"),G=t("af8a"),J=t("a90c"),z=t("619e"),K=t("1785"),X=t("5ab6"),Y=t("6377"),Z=t("b6d6"),nn=t("baef"),en=t("fa7b"),tn=t("5e9f"),rn=t("a529"),on=t("ffa7"),an=t("bc7b"),sn=t("4742"),un=t("0ad0"),cn=t("e49d"),fn=t("4ad8"),ln=t("4336"),pn=t("2f0f");l["a"].use(d["a"],{config:{},iconSet:p["a"],components:{QLayout:m["a"],QLayoutHeader:h["a"],QLayoutDrawer:b["a"],QPageContainer:g["a"],QScrollArea:v["a"],QPage:Q["a"],QToolbar:E["a"],QToolbarTitle:A["a"],QBtn:R["a"],QBtnGroup:L["a"],QIcon:I["a"],QList:D["a"],QListHeader:S["a"],QItem:k["a"],QItemMain:T["a"],QItemSide:O["a"],QField:_["a"],QInput:w["a"],QBtnDropdown:P["a"],QItemTile:y["a"],QTabs:C["a"],QTab:U["a"],QTabPane:V["a"],QCheckbox:x["a"],QRouteTab:F["a"],QTable:M["a"],QTh:H["a"],QTr:N["a"],QTd:j["a"],QTableColumns:B["a"],QSearch:W["a"],QSelect:$["a"],QPopupEdit:q["a"],QPopover:G["a"],QFab:J["a"],QFabAction:z["a"],QColorPicker:K["a"],QAjaxBar:X["a"],QSpinner:Y["a"],QSpinnerGears:Z["a"],QSpinnerAudio:nn["a"],QTree:en["a"],QTooltip:tn["a"],QDialog:rn["a"],QToggle:on["a"],QModal:an["a"]},directives:{Ripple:sn["a"],CloseOverlay:un["a"]},plugins:{Notify:cn["a"],Dialog:fn["a"],LocalStorage:ln["a"],AppFullscreen:pn["a"]}});var dn=function(){var n=this,e=n.$createElement,t=n._self._c||e;return t("div",{attrs:{id:"q-app"}},[t("router-view"),t("q-ajax-bar",{attrs:{color:"primary"}})],1)},mn=[];dn._withStripped=!0;t("67c8");var hn={name:"App",data:function(){return{dark:!1}},methods:{},created:function(){var n=this;console.info(Object({NODE_ENV:"production",CLIENT:!0,SERVER:!1,DEV:!1,PROD:!0,THEME:"mat",MODE:"spa",VUE_ROUTER_MODE:"history",VUE_ROUTER_BASE:"/",APP_URL:"undefined"})),this.$store.dispatch("user/tryAutoLogin").then(function(e){!0===e||n.$router.replace("/login")})}},bn=hn,gn=(t("1c72"),t("a6c2")),vn=Object(gn["a"])(bn,dn,mn,!1,null,null,null);vn.options.__file="App.vue";var Qn=vn.exports,En=t("94ea"),An={},Rn=t("40e4"),Ln=t("476c"),In=t("cd45"),Dn={namespaced:!0,state:An,getters:Rn,mutations:Ln,actions:In},Sn={userId:null,auth:null,firstname:null,lastname:null,permissions:{},roles:[],isDark:!0,isLight:!1},kn=function(n){return n.userId},Tn=function(n){var e={firstname:n.firstname,lastname:n.lastname};return e},On=function(n){return n.permissions},_n=function(n){return n.isDark},wn=function(n){return n.isLight},Pn=function(n){return null!==n.auth},yn=function(n){return function(e){return!!n.permissions[e]&&n.permissions[e].hasAccess}},Cn=function(n){return function(e){return n.permissions[e]?n.permissions[e].color:"#4fc08d"}},Un=function(n){return function(e,t){if(!n.permissions[e])return!1;var r=n.permissions[e];return!!r.pages[t]&&r.pages[t].hasAccess}},Vn=(t("8ade"),t("9f14")),xn=t.n(Vn),Fn=(t("2bf3"),t("baa4"),function(n,e){n.auth=e.auth,n.userId=e.userId,n.permissions=e.permissions,n.roles=e.roles}),Mn=function(n){n.auth=null,n.userId=null,n.permissions={},n.roles=[]},Hn=function(n,e){n.isDark=e,n.isLight=!e},Nn=function(n,e){var t=e[0].data,r=e[0].roleId;Object.entries(t).forEach(function(e){var o=xn()(e,2),a=o[0],s=o[1];if(n.permissions[a]){!0===t[a].hasAccess&&(n.permissions[a].hasAccess=!0,n.permissions[a].fromRoles.find(function(n){return n===r})||n.permissions[a].fromRoles.push(r)),!1===t[a].hasAccess&&1===n.permissions[a].fromRoles.length&&(n.permissions[a].fromRoles=[],n.permissions[a].hasAccess=!1);var i=s.pages;Object.entries(i).forEach(function(e){var t=xn()(e,2),o=t[0],s=t[1];n.permissions[a].pages[o]?n.permissions[a].pages[o].fromRoles.find(function(n){return n===r})?1===n.permissions[a].pages[o].fromRoles.length&&l["a"].set(n.permissions[a].pages,o,s):!0===s.hasAccess&&(n.permissions[a].pages[o].hasAccess=!0,n.permissions[a].pages[o].fromRoles.push(r)):l["a"].set(n.permissions[a].pages,o,s)})}else l["a"].set(n.permissions,a,s)})},jn=(t("0dbc"),t("5516")),Bn=t("7233"),Wn=function(n,e){var t=n.commit,r=n.dispatch;return console.log(e),new Promise(function(n,o){jn["a"].post("/auth/login",{login:e.login,password:e.password}).then(function(e){var o=e.data;t("authUser",{auth:o.auth,userId:o.userId,permissions:o.permissions,roles:o.roles}),ln["a"].set("auth",o.auth),ln["a"].set("userId",o.userId),ln["a"].set("permissions",o.permissions),ln["a"].set("roles",o.roles),r("setAxiosHeader",e.data.auth),r("sockets/connectSocket",o.auth,{root:!0}),n(e)}).catch(function(n){o(n)})})},$n=function(n){var e=n.commit,t=n.dispatch,r=ln["a"].get.item("auth");return null!==r&&(e("authUser",{auth:r,userId:ln["a"].get.item("userId"),permissions:ln["a"].get.item("permissions"),roles:ln["a"].get.item("roles")}),t("setAxiosHeader",r),t("sockets/connectSocket",r,{root:!0}),!0)},qn=function(n){var e=n.commit,t=n.dispatch;ln["a"].clear(),e("clearAuthData"),t("setAxiosHeader","")},Gn=function(n,e){var t=n.commit;t("isDark",e)},Jn=function(n,e){jn["a"].defaults.headers.common["Authorization"]=e},zn=function(n,e){n.commit;return e===Bn["a"]},Kn={namespaced:!0,state:Sn,getters:r,mutations:o,actions:a},Xn={consoleLog:[],updaters:{},updaterData:{},progress:{},progressIsComplete:{}},Yn=(t("d946"),function(n){return n.consoleLog.slice().reverse()}),Zn=function(n){return function(e){return n.updaterData[e]}},ne=function(n){return function(e,t){if(!n.updaters[e])return t;var r=n.updaters[e],o=r.key,a=r.data[o],s=t.findIndex(function(n){return n[o]===a});return s>-1&&l["a"].set(t,s,r.data),t}},ee=function(n){return function(e){return n.progress[e]?n.progress[e]:0}},te=function(n,e){if(!0===e.replace){for(var t=n.consoleLog.length,r=t-1;r>=0;r--)if(!1===n.consoleLog[r].isError){n.consoleLog[r].message=e.message;break}}else n.consoleLog.push(e),console.log(e.message)},re=function(n,e){var t=e[0].updaterId;l["a"].set(n.updaters,t,e[0])},oe=function(n,e){var t=e[0].progressId;l["a"].set(n.progress,t,e[0].progress),l["a"].set(n.progressIsComplete,t,e[0].isComplete)},ae=function(n){n.consoleLog=[]},se=t("68c4"),ie=function(n,e){var t=n.commit,r=n.rootState;l["a"].use(se["a"],{debug:!0,url:"ws://localhost:9090",realm:"realm1",onopen:function(n,e){console.log("WAMP connected",n,e)},onclose:function(n,e){console.log("WAMP closed: "+n,e)}}),l["a"].Wamp.subscribe("console_"+e,function(n,e,r){t("consoleLog",n[0])},{acknowledge:!0}).then(function(n){}),l["a"].Wamp.subscribe("updater_"+e,function(n,e,r){t("updater",n)},{acknowledge:!0}).then(function(n){}),l["a"].Wamp.subscribe("progress_"+e,function(n,e,r){t("progress",n)},{acknowledge:!0}).then(function(n){}),r.user.roles.forEach(function(n,e){console.log(n),l["a"].Wamp.subscribe("role_"+n,function(n,e,r){t("user/role",n,{root:!0})},{acknowledge:!0}).then(function(n){})})},ue=function(n){var e=n.commit;e("clearConsoleLog")},ce={namespaced:!0,state:Xn,getters:s,mutations:i,actions:u},fe={activeSession:null,activeSessionName:null,coachOutFilter:[],coachRetFilter:[]},le=function(n){return n.activeSession},pe=function(n){return n.coachOutFilter},de=function(n){return n.coachRetFilter},me=function(n,e){n.activeSession=e},he=function(n,e){n.coachOutFilter=e},be=function(n,e){n.coachRetFilter=e},ge=t("f8a3"),ve={namespaced:!0,state:fe,getters:c,mutations:f,actions:ge};l["a"].use(En["a"]);var Qe=new En["a"].Store({modules:{example:Dn,user:Kn,sockets:ce,transport:ve}}),Ee=Qe,Ae=t("4af9"),Re={path:"admin",component:function(){return t.e("4c7193ff").then(t.bind(null,"b21c"))},children:[{path:"",component:function(){return t.e("2d0bce42").then(t.bind(null,"2a52"))}},{path:"active",component:function(){return t.e("37a11f7e").then(t.bind(null,"2fc1"))}},{path:"sync",component:function(){return t.e("7e53232d").then(t.bind(null,"0470"))}},{path:"access",component:function(){return t.e("6a9b0564").then(t.bind(null,"ef5a"))}}]},Le={path:"transport",component:function(){return t.e("0bacbe55").then(t.bind(null,"9f04"))},children:[{path:"",component:function(){return t.e("460b2ba8").then(t.bind(null,"594b"))}}]},Ie={path:"students",component:function(){return t.e("61258ee6").then(t.bind(null,"62e6"))},children:[{path:"profile",component:function(){return t.e("57c3081c").then(t.bind(null,"d97c"))}},{path:"list",component:function(){return t.e("ef124a26").then(t.bind(null,"e3af"))}}]},De=[{path:"/",component:function(){return t.e("9651a5da").then(t.bind(null,"38b2"))},children:[{path:"",component:function(){return t.e("0cf2d9c2").then(t.bind(null,"9a8f"))}},{path:"exams",component:function(){return t.e("664b2db7").then(t.bind(null,"570e"))},children:[{path:"",component:function(){return t.e("2d217361").then(t.bind(null,"c680"))}},{path:"gcse",component:function(){return Promise.all([t.e("c1b50d58"),t.e("4f2cf20a")]).then(t.bind(null,"8c49"))}},{path:"alevel",component:function(){return Promise.all([t.e("c1b50d58"),t.e("4d0a35d2")]).then(t.bind(null,"3be1"))}}]},{path:"locations",component:function(){return t.e("0a0af224").then(t.bind(null,"6d20"))},children:[{path:"chapel",component:function(){return t.e("97da3858").then(t.bind(null,"ef64"))}}]},{path:"user",component:function(){return t.e("272570c2").then(t.bind(null,"3896"))}},Re,Le,Ie]},{path:"/login",name:"login",component:function(){return t.e("92316bba").then(t.bind(null,"c4ea"))},children:[{path:"",component:function(){return t.e("59aa3c98").then(t.bind(null,"91c9"))}}]},{path:"*",component:function(){return t.e("5e880c35").then(t.bind(null,"4258"))}}],Se=De;l["a"].use(Ae["a"]);var ke=new Ae["a"]({mode:"history",base:"/",scrollBehavior:function(){return{y:0}},routes:Se}),Te=ke,Oe=function(){var n="function"===typeof Ee?Ee():Ee,e="function"===typeof Te?Te({store:n}):Te;n.$router=e;var t={el:"#q-app",router:e,store:n,render:function(n){return n(Qn)}};return{app:t,store:n,router:e}},_e=function(n){var e=n.router;n.store,n.Vue;e.beforeEach(function(n,e,t){t()})},we=(t("54d6"),t("c46e"),function(n){n.app,n.router,n.Vue}),Pe=t("79a0"),ye=t.n(Pe),Ce=function(n){n.app,n.router;var e=n.Vue;e.use(ye.a)},Ue=Oe(),Ve=Ue.app,xe=Ue.store,Fe=Ue.router;[jn["b"],_e,we,Ce].forEach(function(n){n({app:Ve,router:Fe,store:xe,Vue:l["a"],ssrContext:null})}),new l["a"](Ve)},cd45:function(n,e){},f8a3:function(n,e){},ff1e:function(n,e,t){}},[[0,"runtime","vendor"]]]);