import{o as s,e as l,d as o,F as c,c as m,a as t,b as a,u as r,L as n,k as u,n as _,j as h,h as d}from"./app.b53879ed.js";import{a as p,_ as i}from"./PrimaryButton.27dfc493.js";const g={class:"fixed top-0 right-0 px-6 py-4 sm:block z-10"},f=d(" Panel admina "),y=d(" Wyloguj si\u0119 "),b={class:"max-w-7xl mx-auto mb-14"},x={class:"mt-14 flex flex-row"},v={key:0},k=d(" Timebox "),w=d(" Szczeg\xF3\u0142y "),$={class:"bg-white p-8 rounded-xl shadow shadow-slate-300"},L={__name:"UserLayout",setup(z){return(e,B)=>(s(),l(c,null,[o("div",g,[e.$page.props.auth.user?(s(),l(c,{key:0},[e.$page.props.auth.user.is_admin?(s(),m(r(n),{key:0,class:"text-sm text-gray-700 dark:text-gray-500 mr-1",href:e.route("admin.index")},{default:t(()=>[a(i,null,{default:t(()=>[f]),_:1})]),_:1},8,["href"])):u("",!0),a(r(n),{class:"text-sm text-gray-700 dark:text-gray-500",href:e.route("logout"),method:"post",as:"button"},{default:t(()=>[a(i,null,{default:t(()=>[y]),_:1})]),_:1},8,["href"])],64)):u("",!0)]),o("div",b,[o("div",x,[e.$page.component!=="Edit"?(s(),l("nav",v,[a(r(n),{href:e.route("home"),class:_([{active:e.$page.component==="Welcome"},"ml-12 md:ml-14 bg-gray-200 p-3 w-32 text-center rounded-tl-lg hover:bg-gray-300"]),method:"get",as:"button",type:"button"},{default:t(()=>[k]),_:1},8,["href","class"]),a(r(n),{href:e.route("details"),class:_([{active:e.$page.component==="Detail"},"bg-gray-200 p-3 w-32 text-center rounded-tr-lg hover:bg-gray-300"]),method:"get",as:"button",type:"button"},{default:t(()=>[w]),_:1},8,["href","class"])])):u("",!0)]),o("div",$,[h(e.$slots,"default",{},void 0,!0)])])],64))}},V=p(L,[["__scopeId","data-v-92bb65b0"]]);export{V as U};