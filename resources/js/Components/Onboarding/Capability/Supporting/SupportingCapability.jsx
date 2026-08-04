import ProductSection from "./ProductSection";
import TechnicalSupportSection from "./TechnicalSupportSection";
import DistributionSection from "./DistributionSection";

export default function SupportingCapability({ business, data, setData }) {
    return (
        <div className="space-y-10">
            {/* ======================================================
             | Product Portfolio™
             ====================================================== */}

            <ProductSection business={business} data={data} setData={setData} />

            {/* ======================================================
             | Technical Support™
             ====================================================== */}

            <TechnicalSupportSection
                business={business}
                data={data}
                setData={setData}
            />

            {/* ======================================================
             | Distribution Capability™
             ====================================================== */}

            <DistributionSection
                business={business}
                data={data}
                setData={setData}
            />
        </div>
    );
}
