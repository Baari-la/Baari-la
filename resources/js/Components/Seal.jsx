{/* SEAL OF INDEPENDENCE COMPONENT */}
<div className="relative w-24 h-24 flex items-center justify-center group">
    {/* Background Glow */}
    <div className="absolute inset-0 bg-yellow-500/20 blur-xl rounded-full group-hover:bg-yellow-500/30 transition-all"></div>
    
    {/* The Seal Body */}
    <div className="relative w-20 h-20 border-2 border-yellow-500/50 rounded-full flex flex-col items-center justify-center bg-[#0a192f] shadow-2xl">
        <svg className="w-8 h-8 text-yellow-500 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        <span className="text-[6px] font-black text-yellow-500 uppercase tracking-tighter text-center leading-none">
            Independent<br/>Intelligence
        </span>
    </div>
    
    {/* Rotating Text Effect (CSS) */}
    <div className="absolute inset-0 animate-[spin_10s_linear_infinite] opacity-30 pointer-events-none">
        <svg viewBox="0 0 100 100" className="w-full h-full">
            <path id="circlePath" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="transparent" />
            <text className="fill-yellow-500 text-[6px] font-bold uppercase tracking-[0.2em]">
                <textPath xlinkHref="#circlePath">
                    • DigestexGlobal • Verified 8-Digit Intelligence • 
                </textPath>
            </text>
        </svg>
    </div>
</div>