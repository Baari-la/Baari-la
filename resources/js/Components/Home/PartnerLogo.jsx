export default function PartnerLogos() {
    return (
        <section className="bg-[#0a192f] border-b border-white/5 py-12">
            <div className="container mx-auto px-6">
                <p className="text-[9px] font-black text-gray-500 uppercase tracking-[0.4em] text-center mb-10">
                    Strategic Technology Partners: US & China Collaboration
                </p>

                <div className="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-30 grayscale hover:grayscale-0 transition duration-700 ease-in-out pb-4">
                    {/* Partner USA: Centric Software */}
                    <img
                        src="/images/partners/centric-logo.png"
                        className="h-6 md:h-7 w-auto hover:scale-110 transition-transform"
                        alt="Centric Software (USA)"
                    />

                    {/* Partner Lokal: API Jakarta */}
                    <img
                        src="/images/partners/api-logo.png"
                        className="h-10 md:h-12 w-auto hover:scale-110 transition-transform"
                        alt="API Jakarta"
                    />

                    {/* Partner China: Pattern Tech */}
                    <img
                        src="/images/partners/china-tech-logo.png"
                        className="h-8 md:h-10 w-auto hover:scale-110 transition-transform"
                        alt="Pattern Tech (China)"
                    />

                    {/* Pemerintah: Kemenperin */}
                    <img
                        src="/images/partners/kemenperin-logo.png"
                        className="h-10 md:h-12 w-auto hover:scale-110 transition-transform"
                        alt="Kemenperin RI"
                    />
                </div>
            </div>
        </section>
    );
}
