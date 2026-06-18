import { usePage } from "@inertiajs/react";

export default function MarketTicker({ cotton, exchangeRate }) {
    const { props } = usePage();

    const isEn = props.locale === "en";

    const marketItems = [
        {
            label: "NY/ICE COTTON",
            value: `$${cotton} USD/LB`,
            color: "text-yellow-500",
        },
        {
            label: isEn ? "EXCHANGE RATE" : "KURS USD/IDR",
            value: `Rp ${parseFloat(exchangeRate || 0).toLocaleString(
                "id-ID",
            )}`,
            color: "text-emerald-400",
        },
    ];

    const tickerContent = (
        <div className="flex items-center">
            <span className="flex items-center gap-2 font-black text-yellow-500 text-[10px] uppercase mx-8">
                <span className="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-ping"></span>

                {isEn ? "Live Market Intelligence" : "Intelijen Pasar Langsung"}
            </span>

            {marketItems.map((item, index) => (
                <span
                    key={index}
                    className="
                        font-bold
                        text-white
                        text-[10px]
                        uppercase
                        mx-8
                        border-l
                        border-white/10
                        pl-8
                    "
                >
                    {item.label}:
                    <span className={`ml-2 ${item.color}`}>{item.value}</span>
                </span>
            ))}
        </div>
    );

    return (
        <>
            <div
                className="
                    bg-[#0a192f]
                    border-b
                    border-white/5
                    py-2
                    overflow-hidden
                    backdrop-blur-md
                "
            >
                <div className="flex animate-marquee whitespace-nowrap">
                    {tickerContent}
                    {tickerContent}
                </div>
            </div>

            <style
                dangerouslySetInnerHTML={{
                    __html: `
                        @keyframes marquee-home {
                            0% {
                                transform: translateX(0);
                            }

                            100% {
                                transform: translateX(-50%);
                            }
                        }

                        .animate-marquee {
                            display: flex;
                            min-width: 200%;
                            animation: marquee-home 30s linear infinite;
                        }
                    `,
                }}
            />
        </>
    );
}
