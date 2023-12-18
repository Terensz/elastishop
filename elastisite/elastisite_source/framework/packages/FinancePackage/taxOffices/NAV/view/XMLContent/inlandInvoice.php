<InvoiceData xmlns="http://schemas.nav.gov.hu/OSA/3.0/data" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://schemas.nav.gov.hu/OSA/3.0/data invoiceData.xsd" xmlns:common="http://schemas.nav.gov.hu/NTCA/1.0/common" xmlns:base="http://schemas.nav.gov.hu/OSA/3.0/base">
	<invoiceNumber><?php echo $invoiceNumber; ?></invoiceNumber>
	<invoiceIssueDate><?php echo $dateOfIssue; ?></invoiceIssueDate>
	<completenessIndicator><?php echo $completenessIndicator; ?></completenessIndicator>
	<invoiceMain>
		<invoice>
<?php if ($correctedInvoiceNumber): ?>
			<invoiceReference>
				<originalInvoiceNumber><?php echo $correctedInvoiceNumber; ?></originalInvoiceNumber>
				<modifyWithoutMaster>false</modifyWithoutMaster>
				<modificationIndex>1</modificationIndex>
			</invoiceReference>
<?php endif; ?>
			<invoiceHead>
				<supplierInfo>
					<supplierTaxNumber>
						<base:taxpayerId><?php echo $issuerTaxpayerId; ?></base:taxpayerId>
						<base:vatCode><?php echo $issuerVatCode; ?></base:vatCode>
						<base:countyCode><?php echo $issuerCountyCode; ?></base:countyCode>
					</supplierTaxNumber>
					<supplierName><?php echo $issuerName; ?></supplierName>
					<supplierAddress>
						<base:detailedAddress>
							<base:countryCode><?php echo $issuerCountry; ?></base:countryCode>
							<base:postalCode><?php echo $issuerZipCode; ?></base:postalCode>
							<base:city><?php echo $issuerCity; ?></base:city>
							<base:streetName><?php echo $issuerStreet; ?></base:streetName>
							<base:publicPlaceCategory><?php echo $issuerStreetSuffix; ?></base:publicPlaceCategory>
							<base:number><?php echo $issuerHouseNumber; ?></base:number>
						</base:detailedAddress>
					</supplierAddress>
					<supplierBankAccountNumber><?php echo $issuerBankAccountNumber; ?></supplierBankAccountNumber>
				</supplierInfo>
				<customerInfo>
					<customerVatStatus><?php echo $customerVatStatus; ?></customerVatStatus>
<?php if ($customerVatDataRequired): ?>
					<customerVatData>
						<customerTaxNumber>
							<base:taxpayerId><?php echo $buyerTaxpayerId; ?></base:taxpayerId>
							<base:vatCode><?php echo $buyerVatCode; ?></base:vatCode>
							<base:countyCode><?php echo $buyerCountyCode; ?></base:countyCode>
						</customerTaxNumber>
					</customerVatData>
					<customerName><?php echo $buyerName; ?></customerName>
					<customerAddress>
						<base:detailedAddress>
							<base:countryCode><?php echo $buyerCountry; ?></base:countryCode>
							<base:postalCode><?php echo $buyerZipCode; ?></base:postalCode>
							<base:city><?php echo $buyerCity; ?></base:city>
							<base:streetName><?php echo $buyerStreet; ?></base:streetName>
							<base:publicPlaceCategory><?php echo $buyerStreetSuffix; ?></base:publicPlaceCategory>
							<base:number><?php echo $buyerHouseNumber; ?></base:number>
						</base:detailedAddress>
					</customerAddress>
<?php endif; ?>
				</customerInfo>
				<invoiceDetail>
					<invoiceCategory>NORMAL</invoiceCategory>
					<invoiceDeliveryDate><?php echo $deliveryDate ?></invoiceDeliveryDate>
					<currencyCode><?php echo $currency ?></currencyCode>
					<exchangeRate><?php echo $currencyExchangeRate ?></exchangeRate>
					<utilitySettlementIndicator>false</utilitySettlementIndicator>
					<paymentDate><?php echo $paymentDate; ?></paymentDate>
					<invoiceAppearance><?php echo $invoiceAppearance; ?></invoiceAppearance>
					<conventionalInvoiceInfo>
						<orderNumbers>
							<orderNumber><?php echo $orderNumber; ?></orderNumber>
						</orderNumbers>
					</conventionalInvoiceInfo>
				</invoiceDetail>
			</invoiceHead>
			<invoiceLines>
				<mergedItemIndicator>false</mergedItemIndicator>
<?php foreach ($invoiceItemsTaxData as $lineIndex => $invoiceItem): ?>
				<line>
					<lineNumber><?php echo $invoiceItem['referencedLineIndex'] ? : $invoiceItem['lineIndex']; ?></lineNumber>
<?php if ($invoiceItem['referencedLineIndex']): ?>
					<lineModificationReference>
						<lineNumberReference><?php echo $invoiceItem['lineIndex']; ?></lineNumberReference>
						<lineOperation>CREATE</lineOperation>
					</lineModificationReference>
<?php endif; ?>
<?php /* <!-- <productCodes>
						<productCode>
							<productCodeCategory>VTSZ</productCodeCategory>
							<productCodeValue>020312340</productCodeValue>
						</productCode>
					</productCodes> --> */ ?>
					<lineExpressionIndicator>true</lineExpressionIndicator>
					<lineNatureIndicator>PRODUCT</lineNatureIndicator>
					<lineDescription><?php echo $invoiceItem['productName']; ?></lineDescription>
					<quantity><?php echo $invoiceItem['quantity']; ?></quantity>
					<unitOfMeasure><?php echo $invoiceItem['unitOfMeasure']; ?></unitOfMeasure>
					<unitPrice><?php echo $invoiceItem['formattedUnitNet']; ?></unitPrice>
					<unitPriceHUF><?php echo $invoiceItem['formattedUnitNet']; ?></unitPriceHUF>
					<lineAmountsNormal>
						<lineNetAmountData>
							<lineNetAmount><?php echo $invoiceItem['formattedItemNet']; ?></lineNetAmount>
							<lineNetAmountHUF><?php echo $invoiceItem['formattedItemNet']; ?></lineNetAmountHUF>
						</lineNetAmountData>
						<lineVatRate>
							<vatPercentage><?php echo $invoiceItem['formattedVatFraction']; ?></vatPercentage>
						</lineVatRate>
						<lineVatData>
							<lineVatAmount><?php echo $invoiceItem['formattedItemVat']; ?></lineVatAmount>
							<lineVatAmountHUF><?php echo $invoiceItem['formattedItemVat']; ?></lineVatAmountHUF>
						</lineVatData>
						<lineGrossAmountData>
							<lineGrossAmountNormal><?php echo $invoiceItem['formattedItemGross']; ?></lineGrossAmountNormal>
							<lineGrossAmountNormalHUF><?php echo $invoiceItem['formattedItemGross']; ?></lineGrossAmountNormalHUF>
						</lineGrossAmountData>
					</lineAmountsNormal>
				</line>
<?php endforeach; ?>			
			</invoiceLines>
			<invoiceSummary>
				<summaryNormal>
<?php foreach ($vatSummary as $vatFraction => $vatSummaryRow): ?>
					<summaryByVatRate>
						<vatRate>
							<vatPercentage><?php echo $vatSummaryRow['vat']; ?></vatPercentage>
						</vatRate>
						<vatRateNetData>
							<vatRateNetAmount><?php echo $vatSummaryRow['fractionNet']; ?></vatRateNetAmount>
							<vatRateNetAmountHUF><?php echo $vatSummaryRow['fractionNet']; ?></vatRateNetAmountHUF>
						</vatRateNetData>
						<vatRateVatData>
							<vatRateVatAmount><?php echo $vatSummaryRow['fractionVat']; ?></vatRateVatAmount>
							<vatRateVatAmountHUF><?php echo $vatSummaryRow['fractionVat']; ?></vatRateVatAmountHUF>
						</vatRateVatData>
						<vatRateGrossData>
							<vatRateGrossAmount><?php echo $vatSummaryRow['fractionGross']; ?></vatRateGrossAmount>
							<vatRateGrossAmountHUF><?php echo $vatSummaryRow['fractionGross']; ?></vatRateGrossAmountHUF>
						</vatRateGrossData>
					</summaryByVatRate>
<?php endforeach; ?>
					<invoiceNetAmount><?php echo $formattedTotalNet; ?></invoiceNetAmount>
					<invoiceNetAmountHUF><?php echo $formattedTotalNet; ?></invoiceNetAmountHUF>
					<invoiceVatAmount><?php echo $formattedTotalVat; ?></invoiceVatAmount>
					<invoiceVatAmountHUF><?php echo $formattedTotalVat; ?></invoiceVatAmountHUF>
				</summaryNormal>
				<summaryGrossData>
					<invoiceGrossAmount><?php echo $formattedTotalGross; ?></invoiceGrossAmount>
					<invoiceGrossAmountHUF><?php echo $formattedTotalGross; ?></invoiceGrossAmountHUF>
				</summaryGrossData>
			</invoiceSummary>
		</invoice>
	</invoiceMain>
</InvoiceData>
