import MarketSection from "./MarketSection";
import BuyerSection from "./BuyerSection";
import ExportSection from "./ExportSection";

export default function CommercialCapability({ business, data, setData }) {
    return (
        <div className="space-y-10">
            {/* ======================================================
             | Market Intelligence™
             ====================================================== */}

            <MarketSection business={business} data={data} setData={setData} />

            {/* ======================================================
             | Buyer Network™
             ====================================================== */}

            <BuyerSection business={business} data={data} setData={setData} />

            {/* ======================================================
             | Export Capability™
             ====================================================== */}

            <ExportSection business={business} data={data} setData={setData} />
        </div>
    );
}
